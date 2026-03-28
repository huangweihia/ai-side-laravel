# 微信支付 Native（扫码）接入说明

基于 **微信支付 API v3**，使用项目内 **Guzzle + OpenSSL** 实现 Native 下单与支付结果通知，无需额外 Composer 支付 SDK。

## 1. 你需要准备的内容

| 项目 | 说明 |
|------|------|
| 商户号 `mchid` | 微信商户平台 |
| AppID | 与 Native 支付关联的公众号或移动应用等（与商户平台产品配置一致） |
| API v3 密钥 | 32 位，商户平台「账户中心 → API 安全 → 设置 APIv3 密钥」 |
| 商户 API 证书 | 商户平台申请，得到 `apiclient_key.pem`（**私钥**，仅服务端）及证书序列号 |
| 平台证书 | 用于验证回调签名，商户平台下载或按官方文档拉取，保存为 PEM（如 `wechatpay_platform.pem`） |
| 公网 HTTPS | `notify_url` 必须可被微信访问 |

## 2. 环境变量（`.env`）

复制 `.env.example` 中 `WECHAT_PAY_*` 段，按需填写：

- `WECHAT_PAY_ENABLED=true`：开启后前台 VIP 页可走扫码流程。
- `WECHAT_PAY_NOTIFY_URL`：完整 URL，须与商户平台「Native 支付 → 支付回调链接」一致，例如：`https://你的域名/payments/wechat/notify`。
- 证书路径可为**相对项目根**（如 `storage/certs/wechat/apiclient_key.pem`）或绝对路径。
- `WECHAT_PAY_PLAN_*_YUAN`：三档套餐标价（元），与 VIP 页展示及下单金额一致。

## 3. 证书文件放置

建议目录（已 `.gitignore`，勿提交私钥）：

```
storage/certs/wechat/apiclient_key.pem      # 商户 API 私钥
storage/certs/wechat/wechatpay_platform.pem # 微信平台证书（验签）
```

## 4. 数据库

执行迁移，为订单表增加微信交易号字段：

```bash
php artisan migrate
```

迁移文件：`database/migrations/2026_03_29_000001_add_wechat_transaction_id_to_orders_table.php`。

## 5. 路由与流程（登录用户）

| 路由 | 说明 |
|------|------|
| `GET /vip/pay/{plan}` | `plan` = `monthly` \| `yearly` \| `lifetime`，确认页 |
| `POST /payments/wechat/create` | 创建订单并跳转二维码页 |
| `GET /payments/wechat/order/{orderNo}` | 展示二维码（前端轮询支付状态） |
| `GET /payments/wechat/order/{orderNo}/status` | JSON：`paid` / `pending` |
| `POST /payments/wechat/notify` | 微信服务器回调（**无 CSRF**，已在 `VerifyCsrfToken` 中排除） |

支付成功后：`Order` 置为已支付、`Subscription` 生效，并按套餐延长 VIP（终身会员：`role=vip` 且长期有效；管理员账号仅延长到期日不改角色）。

## 6. 代码入口（便于排查）

- 配置：`config/wechat_pay.php`
- 客户端：`App\Services\WechatPay\WechatPayV3Client`
- 业务：`App\Services\VipWechatPaymentService`
- 控制器：`App\Http\Controllers\Payments\WechatNativePaymentController`
- 视图：`resources/views/payments/vip-pay-confirm.blade.php`、`wechat-native.blade.php`

## 7. 生产环境注意

- 二维码当前通过第三方服务生成图片 URL，若需内网或合规要求，可改为本地生成二维码图片。
- 回调必须 **TLS 有效**；微信会校验签名，请保证平台证书与商户平台当前版本一致（证书会轮换，需按官方指引更新）。

## 8. 与「邮件订阅」区分

- **付费 VIP**：`subscriptions` 表 + `orders` 表，本接入仅涉及该链路。
- **邮件订阅**：`email_subscriptions` / `EmailSubscription`，与微信支付无关。

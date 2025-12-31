# 🛒 tg_stock_notify_multi

一个用于 **独角兽发卡（Unicorn Card）** 的 **Telegram 库存通知脚本**。  
支持 **多商品监控、补货通知、售罄通知、按钮式购买链接**，适合长期无人值守运行。

- GitHub：https://github.com/Netflixxp/tg_stock_notify_multi
- 脚本：`tg_stock_notify_multi.php`

---

## ✨ 功能特性

- ✅ 支持 **监控多个商品**
- ✅ **补货通知**（库存从 `0 → 有货`）
- ✅ **售罄通知**（库存从 `有货 → 0`）
- ✅ 显示 **北京时间**
- ✅ 显示 **距上次售罄 X 分钟**
- ✅ Telegram **按钮式购买链接（Inline Keyboard）**
- ✅ 每个商品 **独立判断状态，不刷屏**
- ✅ 轻量日志，支持 `logrotate`
- ❌ 不会因库存减少 1 个频繁通知

---

## 📦 适用环境

- PHP ≥ 7.2（CLI）
- MySQL / MariaDB
- 独角兽发卡（默认 `goods` / `carmis` 表结构）
- Linux（Ubuntu / Debian / CentOS 等）
- Telegram Bot + Channel

---

## 📥 下载脚本（默认root路径下）

### 1.使用 curl（推荐）

```bash
curl -o tg_stock_notify_multi.php \
https://raw.githubusercontent.com/Netflixxp/tg_stock_notify_multi/main/tg_stock_notify_multi.php
```
### 2.使用 wget（一样）
```bash
wget -O tg_stock_notify_multi.php \
https://raw.githubusercontent.com/Netflixxp/tg_stock_notify_multi/main/tg_stock_notify_multi.php
```
## ⚙️ 配置说明（必须修改）
```bash
nano tg_stock_notify_multi.php
```
>⚠️ 只需要修改下面 3 个部分，其余代码无需改。
#### 1) 数据库配置（必改）
```bash
$dbHost = '127.0.0.1';
$dbUser = '独角兽数据用户名';
$dbPass = '独角兽数据库密码';
$dbName = '独角兽数据库名字';
````
说明：一般在独角兽发卡项目的 .env 里能找到
#### 2) Telegram 配置（必改）
```bash
$tgToken  = 'YOUR_TELEGRAM_BOT_TOKEN';
$tgChatId = '-100XXXXXXXXXX';
```
说明：怎么申请TG机器人不细说了
频道群组的TD 找机器人 @userinfobot 获取
#### 3) 商品配置（支持多个）
```bash
$goodsList = [
    1 => ['url' => 'https://your-domain.com/buy/1'],
    2 => ['url' => 'https://your-domain.com/buy/2'],
];
```
说明：点击具体商品，看网址，后面`buy/1`的数字`1`就是商品对应的ID了，如果是`buy/2`,那么，前面的数字要改成`2`，还有其他产品，顺着填就行，注意后面的`,`就行
## ▶ 手动测试
```bash
php tg_stock_notify_multi.php
```
## ⏱ 定时运行
每 2 分钟检查一次：
```bash
crontab -e
```
添加：
```bash
*/2 * * * * /usr/bin/php /path/to/tg_stock_notify_multi.php >/dev/null 2>&1
```
## 📝 日志
```bash
/var/log/tg_stock_notify.log
```
## ♻️ 日志轮转（保留 7 天)
创建配置文件：
```bash
sudo nano /etc/logrotate.d/tg_stock_notify
```
写入：
```bash
/var/log/tg_stock_notify.log {
    daily
    rotate 7
    missingok
    notifempty
    copytruncate
    nocompress
}
```
## 🧠 通知逻辑说明
| 场景            | 是否通知   |
| ------------- | ------ |
| 卖出 1 个        | ❌ 不通知  |
| 有货状态下补货       | ❌ 不通知  |
| 售罄（>0 → 0）    | ✅ 售罄通知 |
| 断货后补货（0 → >0） | ✅ 补货通知 |
>本脚本采用 状态型通知策略，避免刷频道，符合真实人工运营逻辑。
## 🔒 安全说明
脚本'只读数据库'
不涉及支付 / 发货逻辑
不修改独角兽发卡任何源码
可安全用于生产环境
## ❤️ 致谢
如果你正在使用 独角兽发卡 + Telegram 频道，
希望这个脚本能帮你把 库存通知做到像人工运营一样自然。

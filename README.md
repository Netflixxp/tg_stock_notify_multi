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

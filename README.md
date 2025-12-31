# 🛒 Unicorn Card – Telegram Stock Notifier

一个用于 **独角兽发卡（Unicorn Card）** 的 **Telegram 库存通知脚本**，  
支持 **多商品监控、补货通知、售罄通知、按钮式购买链接**，适合长期无人值守运行。

---

## ✨ 功能特性

- ✅ 支持 **监控多个商品**
- ✅ **补货通知**（库存从 `0 → 有货`）
- ✅ **售罄通知**（库存从 `有货 → 0`）
- ✅ 显示 **北京时间**
- ✅ 显示 **距上次售罄 X 分钟**
- ✅ Telegram **按钮式购买链接（Inline Keyboard）**
- ✅ 每个商品 **独立状态判断，不刷屏**
- ✅ 轻量日志，支持 `logrotate`
- ❌ 不会因库存减少 1 个频繁通知

---

## 📦 适用环境

- PHP ≥ 7.2（CLI）
- MySQL / MariaDB
- 独角兽发卡（基于 `goods` / `carmis` 表）
- Linux（Ubuntu / Debian / CentOS 等）
- Telegram Bot + Channel

---

## 📊 数据表假设（独角兽发卡默认）

### `goods` 表

- `id`
- `gd_name`
- `deleted_at`

### `carmis` 表

- `goods_id`
- `status`（`1 = 未售出`）
- `deleted_at`

---

## 🚀 快速开始

### 1️⃣ 克隆仓库

```bash
git clone https://github.com/yourname/unicorn-tg-stock-notifier.git
cd unicorn-tg-stock-notifier

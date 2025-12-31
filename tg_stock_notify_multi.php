<?php
/*********************************************************
 * 独角兽发卡 - TG 多商品库存通知脚本（终极版）
 *********************************************************/

/********************
 * 基础配置(必须改)
 ********************/
date_default_timezone_set('Asia/Shanghai');

$dbHost = '127.0.0.1';
$dbUser = '独角兽发卡的数据库用户名';
$dbPass = '独角兽发卡的数据库密码';
$dbName = '独角兽发卡的数据库名字';

$tgToken  = '你的TG_BOT_TOKEN';
$tgChatId = '-100xxxxxxxxxx';

$logFile = '/var/log/tg_stock_notify.log';

/********************
 * 商品配置（必须改）
 ********************/
$goodsList = [
    1 => ['url' => 'https://xxx/buy/1'],
    2 => ['url' => 'https://xxx/buy/2'],
    // 继续加
];

/********************
 * 日志
 ********************/
function log_msg($msg) {
    global $logFile;
    file_put_contents(
        $logFile,
        '[' . date('Y-m-d H:i:s') . "] {$msg}\n",
        FILE_APPEND
    );
}

/********************
 * 发送 TG（按钮）
 ********************/
function tg_send($text, $btnText = null, $btnUrl = null) {
    global $tgToken, $tgChatId;

    $data = [
        'chat_id' => $tgChatId,
        'text'    => $text
    ];

    if ($btnText && $btnUrl) {
        $data['reply_markup'] = json_encode([
            'inline_keyboard' => [[
                ['text' => $btnText, 'url' => $btnUrl]
            ]]
        ], JSON_UNESCAPED_UNICODE);
    }

    $ctx = stream_context_create([
        'http' => [
            'method'  => 'POST',
            'header'  => "Content-Type: application/x-www-form-urlencoded",
            'content' => http_build_query($data),
            'timeout' => 5
        ]
    ]);

    return @file_get_contents(
        "https://api.telegram.org/bot{$tgToken}/sendMessage",
        false,
        $ctx
    ) !== false;
}

/********************
 * DB
 ********************/
$db = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
if ($db->connect_error) {
    log_msg("数据库连接失败");
    exit;
}
$db->set_charset('utf8mb4');

/********************
 * 主循环：逐个商品检测
 ********************/
foreach ($goodsList as $goodsId => $conf) {

    $stockFile = "/tmp/goods_{$goodsId}_stock.cache";
    $emptyFile = "/tmp/goods_{$goodsId}_empty_time.cache";

    // 查询库存 + 商品名
    $sql = "
    SELECT g.gd_name AS name, COUNT(c.id) AS stock
    FROM goods g
    LEFT JOIN carmis c
      ON g.id = c.goods_id
     AND c.status = 1
     AND c.deleted_at IS NULL
    WHERE g.id = {$goodsId}
      AND g.deleted_at IS NULL
    GROUP BY g.id, g.gd_name
    ";

    $res = $db->query($sql);
    if (!$res) {
        log_msg("商品 {$goodsId} 库存查询失败");
        continue;
    }

    $row = $res->fetch_assoc();
    $name = $row['name'] ?? "商品{$goodsId}";
    $nowStock = intval($row['stock'] ?? 0);

    $lastStock = file_exists($stockFile)
        ? intval(trim(file_get_contents($stockFile)))
        : 0;

    log_msg("商品={$name} 当前={$nowStock} 上次={$lastStock}");

    /** 售罄 **/
    if ($lastStock > 0 && $nowStock == 0) {
        file_put_contents($emptyFile, time());

        $text =
            "❌【商品已售罄】\n\n" .
            "🔥 商品：{$name}\n" .
            "📦 当前库存：0\n" .
            "🕒 售罄时间：" . date('Y-m-d H:i:s') . "（北京时间）";

        tg_send($text);
    }

    /** 补货 **/
    if ($lastStock == 0 && $nowStock > 0) {

        $gapText = '';
        if (file_exists($emptyFile)) {
            $gap = floor((time() - intval(file_get_contents($emptyFile))) / 60);
            if ($gap < 1) $gap = 1;
            $gapText = "⏱ 距上次售罄：{$gap} 分钟\n";
        }

        $text =
            "📦【补货通知】\n\n" .
            "🔥 商品：{$name}\n" .
            "📊 当前库存：{$nowStock} 份\n" .
            $gapText .
            "🕒 补货时间：" . date('Y-m-d H:i:s') . "（北京时间）\n" .
            "⚡ 自动发货 · 即买即用";

        tg_send($text, '🛒 立即购买', $conf['url']);
    }

    file_put_contents($stockFile, $nowStock);
}

<html>
    <head>
        <title>Koinfit Telegram Bot</title>
    </head>
    <body>
        <h1>Loading...</h1>
    </body>
</html>
<?php
$update = file_get_contents("php://input");
$updateArray = json_decode($update, true);
$user_id = $updateArray['message']['from']['id'];
$text = $updateArray['message']['text'];
$text = explode(" ", $text);
$opcode = $text[0];
$operand = $text[1];



//Get Fees List 
$fees_list = file_get_contents("https://koinfit.com/api/v2/fees/withdraw");
$fees_lists = json_decode($fees_list, true);
$feessize = sizeof($fees_lists);
$feelist ="*Withdraw Fees:* \n Name | Fees \n";
for ($i=0; $i<$feessize; $i++){
    $feelist=$feelist." ".$fees_lists[$i][currency]." | `".$fees_lists[$i][fee][value]. "`\n";
}
$feeslist= urlencode($feelist);


// Get Market List
$markets_list = file_get_contents("https://koinfit.com/api/v2/markets");
//makets_lists will be used in down, do dont delete it
$markets_lists=$markets_list;
$markets_list = json_decode($markets_list, true);
$size=sizeof($markets_list);
$marketslist ="*Koinfit Exchange Markets:* \n Market Name | Market ID \n";
for ($i=0; $i<$size; $i++){
$marketslist=$marketslist." ".$markets_list[$i][name]." | `".$markets_list[$i][id]. "`\n";
}
$marketslist= urlencode($marketslist);


//Get Market Ticker
if ($opcode == '/ticker' && $operand==''){
$operand='btcinr';
}
$market_name = $operand;
$market = file_get_contents("https://koinfit.com/api/v2/tickers/" .$market_name);
$market = json_decode($market, true);
if($market!=''){
$market_price = $market['ticker']['last'];
$market_buy = $market['ticker']['buy'];
$market_sell = $market['ticker']['sell'];
$market_low = $market['ticker']['low'];
$market_high = $market['ticker']['high'];
$market_volume = $market['ticker']['volume'];
$market_change = $market['ticker']['price_change_percent'];
$market_name = strtoupper($market_name);
$message = " *Market Name :* " . $market_name . "`\n *Current Price :* " . $market_price.
"\n *24 Hour Change :* " . $market_change . "\n *Buy Price :* ". $market_sell. "\n *Sell Price :* " . $market_buy . "\n *High :* " . $market_high . "\n *Low :* " . $market_low . "\n *Volume :* " . $market_volume. "`";
$inline_keyboard = json_encode([
'inline_keyboard'=>[
[
 ['text'=>'Trade now ' . $market_name, 'url'=>'https://koinfit.com/trading/' . $market_name],
 ],
 ]
]);
}
else{
$error=1;
$message = " *Error* : Invaild Market Selected. \n Please provide correct marketid to get ticker. \n See /help for Help regarding marketid.";
$inline_keyboard='';
}
$message = urlencode($message);
$help = " *Available Commands:* \n /ticker marketid (get ticker of specific market) \n /markets (get market name and marketid of all markes) \n /help (shows help message) \n More features coming soon...";
$help = urlencode($help);
switch (true)
{
    case $opcode == '/ticker':
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&reply_markup=" . $inline_keyboard . "&parse_mode=Markdown&text=" . $message;
        file_get_contents($api);
        break;
        
    case $opcode == '/fees':
        $inline_keyboard = json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>'Check on Koinfit ' . $market_name, 'url'=>'https://koinfit.com/fees'],
                    ],
                ]
            ]
        );
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&reply_markup=" . $inline_keyboard . "&parse_mode=Markdown&text=" . $feeslist;
        $break;
    case $opcode == '/markets':
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinf&parse_mode=Markdown&text=" . $marketslist;
        file_get_contents($api);
        break;
    case $opcode == '/help':
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&parse_mode=Markdown&text=" . $help;
        file_get_contents($api);
        break;
    case $opcode == '/fees@Koinfit_bot':
$inline_keyboard = json_encode([
            'inline_keyboard'=>[
                [
                    ['text'=>'Check on Koinfit ' . $market_name, 'url'=>'https://koinfit.com/fees'],
                    ],
                ]
            ]
        );
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&reply_markup=" . $inline_keyboard . "&parse_mode=Markdown&text=" . $feeslist;
        $break;
    case $opcode == '/help@Koinfit_bot':
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&parse_mode=Markdown&text=" . $help;
        file_get_contents($api);
        break;
    case $opcode == '/markets@Koinfit_bot':
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&parse_mode=Markdown&text=" . $marketslist;
        file_get_contents($api);
        break;
   case $opcode == '/ticker@Koinfit_bot':
        $api = "https://api.telegram.org/bot855800963:AAE9CldKjFLv-6O8QNjRBjhRNR_64twWj1M/sendmessage?chat_id=@koinfit&parse_mode=Markdown&text=" . $help;
        file_get_contents($api);
        break;
    default:
        echo 'nothing';
        break;
}

?>

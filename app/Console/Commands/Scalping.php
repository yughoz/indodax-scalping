<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Scalping extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scalping:starto {koin?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';


    public  $balance_idr = 0;
    public  $balance_coin = 0;
    public  $coin = "";
    public  $difference = 0;
    private $price_buy = 0;
    private $price_sell = 0;
    private $your_buy = 0;
    private $your_sell = 0;
    private $ready_buy = false;
    private $ready_sell = false;
    private $status_buy = false; //order_id buy
    private $status_sell = false; //order_id sell
    private $cancel_buy_all = false; //order_id buy
    private $cancel_sell_all = false; //order_id buy
    private $skip_buy = false; //order_id buy
    private $skip_sell = false; //order_id buy
    private $min_sell = 1; //minimal jual di harga ini + 1%



    private $url_private = 'https://indodax.com/tapi';
    private $url_list_data = 'http://localhost:8000/api/';

    // Please find Key from trade API Indodax exchange
    private $key = '';
    // Please find Secret Key from trade API Indodax exchange
    private $secretKey = '';
   
    //email from api 
    private $email = '';
    

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->key = env("INDODAX_KEY");
        $this->secretKey = env("INDODAX_SECRETKEY");
        $this->email = env("INDODAX_MAIL");
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->coin = $this->argument('koin');
        if (empty($this->coin)) {
            $this->coin = $this->ask('Masukan nama koin?');
        }

        $this->skip_buy = $this->ask('skip buy? ') ?? false;
        $this->skip_sell = $this->ask('skip sell?') ?? false;

        $this->report_server();


        if (!empty($this->coin)) {
            for ($i=0; $i < 10000; $i++) { 
                //init

                $this->cancel_buy_all = false;
                $this->cancel_sell_all = false;


                $this->get_balance();
                $this->info("balance idr    =". $this->balance_idr);
                $this->info("balance ".$this->coin."    =". $this->balance_coin);
                // $this->info("coin mu adalah ". $this->coin);
    
    
                // if ($this->ready_buy && $this->ready_sell) {
                //     break;
                // }
                
                
                $this->get_depth_koin($this->coin);
    
                
                $this->get_buy();

                if (!$this->skip_buy) {
                    $this->set_buy(); 
                } else {
                    $this->warn("buy            : SKIP!");

                }
                if (!$this->skip_sell) {
                    $this->set_sell();
                } else {
                    $this->warn("sell            : SKIP!");
                }

                
                $this->info("buy            : ".$this->your_buy);
                $this->info("sell           : ".$this->your_sell);
                // $this->info("min_sell       : ".$this->min_sell);
                $this->warn("============". $this->coin."==========\n");
                $this->report_server();

                sleep(5);

    
            }
        } else {
            $this->coin = $this->ask('koin kosong!');
        }





        // $this->info("Hey, watch this !");
        // $this->comment("Just a comment passing by");
        // $this->question("Why did you do that?");
        // $this->error("Ops, that should not happen.");

    }

    public function get_balance()
    {
        $data = [
	        'method' => 'getInfo',
	        'timestamp' => '1578304294000',
	        'recvWindow' => '1578303937000'
	    ];
        $post_data = http_build_query($data, '', '&');
        $sign = hash_hmac('sha512', $post_data, $this->secretKey);
        
        $headers = ['Key:'.$this->key,'Sign:'.$sign];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $this->url_private,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $res_json = json_decode($response,true);
        if ($res_json['success']) {
            $this->balance_idr = $res_json['return']['balance']['idr'];
            $this->balance_coin= $res_json['return']['balance'][$this->coin];
        }
        // sleep(1);
    }


    public function report_server()
    {
        $url = $this->url_list_data.'engine-scalping';
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => http_build_query([
                'key'       => $this->key,
                'email'     => $this->email,
                'coin'      => $this->coin,
                'coin_name' => $this->coin,
                'secretKey' => $this->secretKey,
            ]),
            CURLOPT_RETURNTRANSFER => true
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        $json = json_decode ($response);
        // echo $response ;die;    

        if (!empty($json->error)) {
            $this->error("ERR = ". $response);
            exit();
            // echo $response ;die;    
        } else {
            
        }
    
        
    }
    public function get_depth_koin($pair_id)
    {
        $url = 'https://indodax.com/api/depth/'.$pair_id."idr";
    
        $curl = curl_init();
    
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
        ));
    
        $response = curl_exec($curl);
    
        curl_close($curl);
        $json = json_decode ($response);

        if (!empty($json->error)) {
            $this->error("ERR = ". $response);
            exit();
            // echo $response ;die;    
        } else {
            $ticker_sell = 0;
            $ticker_buy = 0;
            foreach ($json->buy as $key => $value) {
                $hitung = $value[0] * $value[1];
                if ($hitung > 90000) {
                    $ticker_buy = $value[0];
                    break;    
                }
            }

            foreach ($json->sell as $key => $value) {
                $hitung = $value[0] * $value[1];
                if ($hitung > 90000) {
                    $ticker_sell = $value[0];
                    break;    
                }
            }


            // $this->difference = (($json->ticker->sell-1) - ($json->ticker->buy+1))/($json->ticker->buy+1) * 100;


            $this->difference = (($ticker_sell-1) - ($ticker_buy+1))/($ticker_buy+1) * 100;
            if ($this->difference < 1 ) {
                $this->ready_buy = false;
                $this->error("difference    : ".$this->difference);
            } else {
                $this->ready_buy = true;
                $this->info("difference     : ".$this->difference);
            }

            

            if ($this->difference < 0.5 ) {
                $this->ready_sell = false;
            } else {
                $this->ready_sell = true;

            }


            $this->price_sell       = ($ticker_sell);
            $this->price_buy        = ($ticker_buy);


        }
    }
    

    public function get_buy()
    {
        $data = [
	        'method' => 'openOrders',
	        'pair'  => $this->coin."_idr",
	        'timestamp' => '1578304294000',
	        'recvWindow' => '1578303937000'
	    ];
        $post_data = http_build_query($data, '', '&');
        $sign = hash_hmac('sha512', $post_data, $this->secretKey);
        
        $headers = ['Key:'.$this->key,'Sign:'.$sign];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $this->url_private,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $res_json = json_decode($response,true);
        $this->status_sell = false;
        $this->status_buy = false;
        
        if ($res_json['success']) {
            // $this->info(print_r($res_json['return']['orders']));  
            $this->get_depth_koin($this->coin);

            foreach ($res_json['return']['orders'] as $key => $value) {

                if ($value['type'] == "buy") {
                    if ($value['price'] < $this->price_buy || $this->difference < 1 || $this->cancel_buy_all || $this->skip_buy) { //jika harga tidak paling atas di cancel
                        $this->warn("cancel order buy   :".$value['price'] ."-----". $this->price_buy);  
                        //cancel order
                        $this->cancel_order($value['order_id'],'buy');
                        
                        $this->status_buy = false;
                        $this->cancel_buy_all = true;
                    } else {
                        $this->status_buy = true;
                    }
                    
                } else {
                    $this->status_sell = true;

                    // if ($this->min_sell < $value['price'] ) {
                    if ($this->difference < 0.5 ) {
                        $this->error("cancel order sell   : HOLD ".$value['price'] ."-----". $this->price_sell);  
                        
                    } else {
                        if ($value['price'] > $this->price_sell || $this->cancel_sell_all) { //jika harga tidak paling atas di cancel
                            $this->warn("cancel order sell   :".$value['price'] ."-----". $this->price_sell);  
                            //cancel order
                            $this->cancel_order($value['order_id'],'sell');
                            
                            $this->status_sell = false;
                            $this->cancel_sell_all = true;

                        }
                    }
                }
                    
            }
            // $this->balance_idr = $res_json['return']['balance']['idr'];
            // $this->balance_coin= $res_json['return']['balance'][$this->coin];
        } else {
            $this->error("ERR       : ".$response);  
        }
    }


    public function cancel_order($order_id,$type)
    {
        $data = [
	        'method' => 'cancelOrder',
            'pair' => $this->coin.'_idr',
            'order_id' => $order_id,
            'type'      => $type,
	        'timestamp' => '1578304294000',
	        'recvWindow' => '1578303937000'
	    ];
        $post_data = http_build_query($data, '', '&');
        $sign = hash_hmac('sha512', $post_data, $this->secretKey);
        
        $headers = ['Key:'.$this->key,'Sign:'.$sign];

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_URL => $this->url_private,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_RETURNTRANSFER => true
        ));

        $response = curl_exec($curl);
        
        curl_close($curl);
        $res_json = json_decode($response,true);
        if ($res_json['success']) {
            $this->balance_idr = $res_json['return']['balance']['idr'];
            $this->balance_coin= $res_json['return']['balance'][$this->coin];
        }
    }

    
    public function set_buy()
    {
        // $this->warn("set buy        : ".$this->your_buy);

        // if ($this->ready_buy && !$this->status_buy && $this->balance_idr > 10000) {
        if ($this->ready_buy && $this->balance_idr > 10000) {
            
            $this->your_buy         = ($this->price_buy + 1);


            $data = [
                'method' => 'trade',
                'timestamp' => '1578304294000',
                'recvWindow' => '1578303937000',
                'pair' => $this->coin.'_idr',
                'type' => 'buy',
                'price' => $this->your_buy,
                'idr' => $this->balance_idr,
                // $this->coin => '109.85568181'
            ];
            $post_data = http_build_query($data, '', '&');
            $sign = hash_hmac('sha512', $post_data, $this->secretKey);
            
            $headers = ['Key:'.$this->key,'Sign:'.$sign];
    
            $curl = curl_init();
    
            curl_setopt_array($curl, array(
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_URL => $this->url_private,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true
            ));
    
            $response = curl_exec($curl);
            
            curl_close($curl);
            $res_json = json_decode($response,true);
            if ($res_json['success']) {
                $this->warn("set buy price      : ".$this->your_buy);
                $this->warn("set buy nominal    : ".$res_json['return']['remain_rp']);
                $this->cancel_buy_all = false;
            } else {
                $this->error("ERR       : ".$response);

            }
            
        } else {
            // $this->info("set buy        : ready_buy".$this->ready_buy);
            // $this->info("set buy        : status_buy".$this->status_buy);
            // $this->info("set buy        : balance_idr".$this->balance_idr);
        }

    }

    public function set_sell()
    {

        // if ($this->ready_sell && !$this->status_sell && $this->balance_coin > 0) {
        if ($this->ready_sell && $this->balance_coin > 0) {
            $this->your_sell        = ($this->price_sell- 1);

            $data = [
                'method' => 'trade',
                'timestamp' => '1578304294000',
                'recvWindow' => '1578303937000',
                'pair' => $this->coin.'_idr',
                'type' => 'sell',
                'price' => $this->your_sell,
                // 'idr' => $this->balance_idr,
                $this->coin => $this->balance_coin
            ];
            $post_data = http_build_query($data, '', '&');
            $sign = hash_hmac('sha512', $post_data, $this->secretKey);
            
            $headers = ['Key:'.$this->key,'Sign:'.$sign];
            
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_URL => $this->url_private,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $data,
                CURLOPT_RETURNTRANSFER => true
            ));
            
            $response = curl_exec($curl);
            
            curl_close($curl);
            $res_json = json_decode($response,true);
                // $this->error("ERR       : ".$response);
            if ($res_json['success']) {
                $this->warn("set sell price      : ".$this->your_sell);
                // $this->warn("set sell nominal    : ".$res_json['return']['remain_rp']);
                
            } else {
                $this->error("ERR       : ".$response);
            
            }
            
        } elseif (!$this->ready_sell && $this->balance_coin > 0) { 
            $this->warn("set sell        : HOLD! ");
        
        }else {
            // $this->info("set sell        : else");
        }
    }


}

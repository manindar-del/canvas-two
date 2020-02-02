<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ConvertCurrency;
use GuzzleHttp\Client;
class DataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function postRequest()
   {
        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'http://data.fixer.io/api/latest?access_key=031ef6e6612321aa13a1bb4f1e53d8d5');

        $response = $response->getBody();

        $rates = json_decode($response);

         //dd($rates);

        if (empty($rates->base) || empty($rates->rates)) {
            dd('Something went wrong');
        }

        foreach ($rates->rates as $_currency => $_rate) {
            //dd($rates->base . ' - ' .$_currency . ' : ' . $_rate);

            $this->convertercurrencies = ConvertCurrency::create([
                'base_currency' => $rates->base,
                'target_currency' => $_currency,
                'base_currency_value' => 1,
                'target_currency_value'=> $_rate
            ]);

        }





        // $baseCurrency = 'EUR';


        // if($response === false){
        //     throw new Exception('Unable to connect to API.');
        // }
        // $currencyRates =json_decode($response, true);

        // $gbpRate =  $currencyRates['rates']['GBP'];

        // $inrRate = $currencyRates['rates']['INR'];
        // $kwdRate = $currencyRates['rates']['KWD'];
        // $bhdRate = $currencyRates['rates']['BHD'];
        // $omrRate = $currencyRates['rates']['OMR'];
        // $jodRate = $currencyRates['rates']['JOD'];
        // $kydRate = $currencyRates['rates']['KYD'];
        // $chfRate = $currencyRates['rates']['CHF'];
        // $usdRate = $currencyRates['rates']['USD'];
        // $myrRate = $currencyRates['rates']['MYR'];
        // $euros = 1;
        // $gbp = $euros * $gbpRate;

        // $inr = $euros * $inrRate;
        // $kwd = $euros * $kwdRate;
        // $bhd = $euros * $bhdRate;
        // $omr = $euros * $omrRate;
        // $jod = $euros * $jodRate;
        // $kyd = $euros * $kydRate;
        // $chf = $euros * $chfRate;
        // $usd = $euros * $usdRate;
        // $myr = $euros * $myrRate;

        // var_dump($gbp);
        // var_dump($inr);
        // var_dump($kwd);
        // var_dump($bhd);
        // var_dump($omr);
        // var_dump($jod);
        // var_dump($kyd);
        // var_dump($chf);
        // var_dump($usd);
        // var_dump($myr);
        // echo nl2br(ob_get_clean());
        // echo number_format($gbp, 2);
        // echo"<br>";
        // echo number_format($inr, 2);
        // echo"<br>";
        // echo number_format($kwd, 2);
        // echo"<br>";
        // echo number_format($bhd, 2);
        // echo"<br>";
        // echo number_format($omr, 2);
        // echo"<br>";
        // echo number_format($jod, 2);
        // echo"<br>";
        // echo number_format($kyd, 2);
        // echo"<br>";
        // echo number_format($chf, 2);
        // echo"<br>";
        // echo number_format($usd, 2);
        // echo"<br>";
        // echo number_format($myr, 2);

        // $this->convert_currencies = ConvertCurrency::create([
        //     'base_currency' => 'EURO',
        //     'target_currency' =>'INR',
        //     'base_currency_value' =>'1',
        //     'target_currency_value' => $response->$gbp,


        // ]);



}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
     {


    }


//  /**
//      * Check form data
//      *
//      * @param Request $request
//      * @reutn void
//      */
//     private function check(Request $request)

//     {


//         $rules = [
//             'base_currency' => 'required',
//         ];

//         $request->validate($rules);

//     }


// /**
//      * Create a new payment and set status to pending
//      *
//      * @return Illuminate\Http\Response
//      */
//     private function add(Request $request)
//     {

//         $this->convertercurrencies = ConvertCurrency::create([
//             'base_currency' => $request->base,
//             'target_currency' => $request->target_currency,
//             'base_currency_value' => $request->target_currency,
//             'target_currency_value' => $request->target_currency,

//         ]);

//         }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }







}

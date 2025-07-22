<?php

include 'inc.header.php';

use Codenixsv\CoinGeckoApi\CoinGeckoClient;
use Wruczek\PhpFileCache\PhpFileCache;

date_default_timezone_set('Europe/Riga');

$cache = new PhpFileCache($base_path . 'cache');

$initialSumEUR = 500;

$currentBTCPrice = $cache->refreshIfExpired('currentPrice', function() {
    $client = new CoinGeckoClient();
    $data = $client->simple()->getPrice('bitcoin', 'eur');
    return $data['bitcoin']['eur'];
}, 60);

$currentSatsPerEUR = 100000000 / $currentBTCPrice;

$historicalBTCPrices = [
    '2021-03-18' => 48365.0435,
    '2021-03-19' => 48607.7424,
    '2021-03-22' => 45327.0139,
    '2021-03-23' => 45869.65,
    '2021-03-24' => 44270.8074,
    '2021-03-25' => 43609.7734,
    '2021-03-26' => 46672.0426,
    '2021-03-29' => 48983.9678,
    '2021-03-30' => 50177.0489,
    '2021-03-31' => 50132.8621
];

?>

<div class="container">

<h1>Pabalsts bitkoinos</h1>

<p>Kāda būtu valsts vienreizējā 500 EUR pabalsta par katru bērnu tagadējā vērtība, ja saņemšanas dienā tas tiktu konvertēts uz Bitcoin.</p>

<p>Šobrīd (<?php echo date('d.m.Y \p\lk\s\t. H.i'); ?>) 1 BTC = <?php echo number_format($currentBTCPrice, 2); ?> EUR (dati no <a href="https://www.coingecko.com/">coingecko</a>). Bitcoin kurss te ir aptuvens, tas atšķiras atkarībā no izmantotās biržas vai brokera, netiek arī ierēķinātas pirkšanas un pārdošanas komisijas. Summa pēc IIN ir noņemot 20% no VID ieskatā fiksētās peļņas, ja BTC tiek pārdots pret EUR, par ko ir jānomaksā IIN par kapitāla pieaugumu (papildus informācijai par nodokļiem skat. <a href="https://www.vid.gov.lv/lv/fiziskas-personas-darbibas-ar-kriptovalutam">informāciju VID mājaslapā</a>).</p>

<p>Bitcoin ir iespējams iegādāties daudz un dažādos veidos, viens variants ir <a href="https://hodlhodl.com/join/Y5OI">HodlHodl</a>, kas ir platforma, kas ļauj pirkt un pārdod Bitcoin no citām privātpersonām (reģistrējoties neprasa pases datus).</p>

<table class="table">
    <thead>
        <th>Datums</th>
        <th>Summa (EUR)</th>
        <th>BTC kurss</th>
        <th>Bitcoin apjoms</th>
        <th>Vērtība šobrīd (EUR)</th>
        <th>Starpība</th>
        <th>Vērtība pārdodot, pēc IIN (EUR)</th>
    </thead>
    <tbody>
<?php
    foreach ($historicalBTCPrices as $date => $price) {
?>
        <tr>
            <td><?php echo date('d.m.Y', strtotime($date)); ?></td>
            <td><?php echo number_format($initialSumEUR, 2); ?> EUR</td>
            <td><?php echo number_format($price, 2); ?> EUR</td>
            <td><?php
                $satsPerEURThen = 100000000 / $price;
                $sats = floor($initialSumEUR * $satsPerEURThen);
                echo number_format($sats / 100000000, 8), ' BTC (', $sats, ' satoši)';
            ?></td>
            <td><?php
                $valueEURNow = $sats / $currentSatsPerEUR;
                echo number_format($valueEURNow, 2), ' EUR';
            ?></td>
            <td><?php
                $diff = $valueEURNow - $initialSumEUR;
                $diffPercent = 100 / $initialSumEUR * $diff;
                if ($diff > 0) {
                    echo '<span class="text-success">+';
                }
                elseif ($diff == 0) {
                    echo '<span>=';
                }
                else {
                    echo '<span class="text-danger">';
                }
                echo number_format($diff, 2), ' EUR (', number_format($diffPercent, 2), '%)'; ?></span></td>
            <td><?php
                if ($diff > 0) {
                    $afterTaxesEUR = $initialSumEUR + ($diff * 0.8);
                }
                else {
                    $afterTaxesEUR = $valueEURNow;
                }
                echo number_format($afterTaxesEUR, 2);
            ?> EUR</td>
        </tr>
<?php
    }
?>
    </tbody>
</table>

<p>Autors: <a href="https://twitter.com/kristapsk">Kristaps Kaupe</a>. Pirmkods: <a href="https://github.com/kristapsk/pabalsts.bitkoinos.lv">GitHub</a>.</p>

</div>

<?php
include 'inc.footer.php';

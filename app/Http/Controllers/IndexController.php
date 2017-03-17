<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index()
    {
        // determina a localização e o fuso horário do cliente
        //array com os dados do cliente proveniente do geoplugin
        $geoplugin = unserialize(file_get_contents('http://www.geoplugin.net/php.gp?ip='.$_SERVER['REMOTE_ADDR']));

        // determina o país do cliente
        $terra = $geoplugin['geoplugin_countryCode'];

        // determina a região (estado) do cliente. TODO: verificar se o output para os estados norte-americanos estão corretos.
        $regio = $geoplugin['geoplugin_region'];

        // determina a língua do cliente automaticamente conforme a sua localização.
        //TODO: determinar a língua do cliente em países multilíngues, como a Suíça por exemplo, de acordo com a sua localização em uma determinada região dentro do país.
        $lingua = $terra;

        // determina o fuso horário
        if      ($terra == 'AR') {$utc = 'America/Argentina/Buenos_Aires';}
        elseif  ($terra == 'BR') {$utc = 'America/Sao_Paulo';}
        elseif  ($terra == 'DE') {$utc = 'Europe/Berlin';}
        elseif  ($terra == 'ES') {$utc = 'Europe/Madrid';}
        elseif  ($terra == 'FR') {$utc = 'Europe/Paris';}
        elseif  ($terra == 'IT') {$utc = 'Europe/Rome';}
        elseif  ($terra == 'PT') {$utc = 'Europe/Lisbon';}
        elseif  ($terra == 'UK') {$utc = 'Europe/London';}
        elseif  ($terra == 'US' && $regio == 'NY') {$utc = 'America/New_York';}
        elseif  ($terra == 'US' && $regio == 'IL') {$utc = 'America/Chicago';}
        elseif  ($terra == 'US' && $regio == 'CO') {$utc = 'America/Denver';}
        elseif  ($terra == 'US' && $regio == 'AZ') {$utc = 'America/Phoenix';}
        elseif  ($terra == 'US' && $regio == 'CA') {$utc = 'America/Los_Angeles';}
        elseif  ($terra == 'US' && $regio == 'AK') {$utc = 'America/Anchorage';}
        elseif  ($terra == 'US' && $regio == 'XX') {$utc = 'America/Adak';}
        elseif  ($terra == 'US' && $regio == 'HI') {$utc = 'Pacific/Honolulu';}
        else    {$utc = 'Europe/Vatican';}

        date_default_timezone_set($utc);


        // determina o horário atual
        $nunc = time() + (0 * 60 * 60);

        // determina o ano atual
        $annus = date('o');

        // determina o mês
        $mensis = date('n');

        // determina o dia do mês
        $dies = date('j');

        // determina o DIA DA SEMANA
        $dies_hebdomadis = date('N', $nunc); // domingo = 7, segunda = 1, terça = 2, etc.

        // primeiro dia do ano (= 00:00:00 do dia 01 de janeiro do ano atual)
        $kalendae = strtotime('first day of January'.$annus);

        // epifania: 1° domingo depois de 1° de janeiro
        $epiphania = strtotime('sunday', mktime(0,0,0,1,2,$annus));
        //TODO: acertar cálculo para os países que comemoram a epifania sempre no dia 6,como Itália, Irlanda, etc.

        // batismo do senhor: 1° domingo depois do dia 6 de janeiro.
        // se, entretanto, epifania e batismo do senhor caem no mesmo dia, o batismo do senhor é transferido para o dia seguinte.
        $baptismus_domini =  strtotime('sunday', mktime(0,0,0,1,6,$annus));
        if ($epiphania == $baptismus_domini)
        {
            $baptismus_domini = strtotime('+1 day', $epiphania);
        }

        // 1° dia do tempo comum I
        $per_annum_I = strtotime('+1 day', $baptismus_domini);

        // 2° domingo do tempo comum I.
        // necessário para o cálculo das semanas do tempo comum. não há 1° domingo do tempo comum!
        $dominica_II_per_annum = strtotime('sunday', $per_annum_I);

        // páscoa
        $pascha = easter_date($annus);
        //TODO: acertat cálculo para os diferentes fusos horário. problema acarretado por um erro do PHP que determina a hora da páscoa somente em relaçao a GMT.

        // quarta-feira de cinzas. apesar da data ser anterior à páscoa a variável é calculada depois, pois depende dela (da páscoa).
        $feria_IV_cinerum = strtotime('-46 day', $pascha);

        // 1° domingo da quaresma. também necessário para o cálculo das semanas da quaresma.
        $dominica_I_in_quadragesima = strtotime('sunday', $feria_IV_cinerum);    

        // domingo de ramos
        $dominica_palmarum = strtotime('-1 week', $pascha);

        // pentecostes
        $pentecoste = strtotime('+49 day', $pascha);

        // 1° dia do tempo comum II
        $per_annum_II = strtotime('+1 day', $pentecoste);

        // 4° domingo do advento do ano atual (= 1° domingo antes do natal)
        $dominica_IV_adventus = strtotime('last sunday', mktime(0,0,0,12,25,$annus));

        // 1° domingo do advento do ano atual (= 1° domingo antes do natal)
        $dominica_I_adventus = strtotime('-21 day', $dominica_IV_adventus);

        // subdivisões dentro do tempo do advento
        // do primeiro domingo do advento até o dia 16 de dezembro, inclusive
        $XVII_decembris = mktime(0,0,0,12,17,$annus);

        // do dia 16 ao dia 23 de dezembro, inclusive
        $XXIV_decembris = mktime(0,0,0,12,24,$annus);


        // natal
        $nativitatis = mktime(0, 0, 0, 12, 25, $annus);

        // último dia do ano (= 23:59:59 do dia 31 de dezembro do ano atual)
        $silvester = strtotime('last day of December'.$annus.'23:59:59');

        // Determina se o ano litúrgico é A, B ou C
        if (($annus % 3) == 0 & $nunc < $dominica_I_adventus) {$cyclus_annualis = 'C';}
        elseif (($annus % 3) == 0 & $nunc >= $dominica_I_adventus) {$cyclus_annualis = 'A';}
        elseif (($annus % 3) == 1 & $nunc < $dominica_I_adventus) {$cyclus_annualis = 'A';}
        elseif (($annus % 3) == 1 & $nunc >= $dominica_I_adventus) {$cyclus_annualis = 'B';}
        elseif (($annus % 3) == 2 & $nunc < $dominica_I_adventus) {$cyclus_annualis = 'B';}
        elseif (($annus % 3) == 2 & $nunc >= $dominica_I_adventus) {$cyclus_annualis = 'C';}

        // determina se o ciclo ferial para os dias da semana é I ou II
        if (($annus % 2) == 0) {$ciclus_ferialis = 'II';}
        else {$ciclus_ferialis = 'I';}

        // Determina o TEMPO litúrgico
        // Tempo do Advento
        if ($dominica_I_adventus <= $nunc && $nunc < $nativitatis) {$tempus = 'adventus';}

        // Tempo Comum I
        elseif ($per_annum_I <= $nunc && $nunc < $feria_IV_cinerum) {$tempus = 'per_annum';}

        // tempo da quaresma
        elseif ($feria_IV_cinerum <= $nunc && $nunc < $pascha) {$tempus = 'quadragesimae';}

        // tempo pascal
        elseif ($pascha <= $nunc && $nunc < $per_annum_II) {$tempus = 'paschale';}

        // tempo comum II
        elseif ($per_annum_II <= $nunc && $nunc < $prima_dominica_adventus) {$tempus = 'per_annum';}

        // tempo do natal
        else {$tempus = 'nativitatis';}


        // determina as subdivisões dos tempos litúrgicos
        // subdivisões do advento
        if ($dominica_I_adventus <= $nunc && $nunc < $XVII_decembris) {$subdivisio = 1;}
        elseif ($XVII_decembris <= $nunc && $nunc < $XXIV_decembris) {$subdivisio = 2;}
        elseif ($XXIV_decembris <= $nunc && $nunc < $nativitatis) {$subdivisio = 3;}

        // subdivisões do natal
        elseif ($nativitatis <= $nunc && $nunc < $epiphania) {$subdivisio = 1;}
        elseif ($epiphania <= $nunc && $nunc < $baptismus_domini) {$subdivisio = 2;}

        // subdivisões da quaresma
        elseif ($feria_IV_cinerum <= $nunc && $nunc < $dominica_palmarum) {$subdivisio = 1;}

        // subdivisões da páscoa (há?)

        else {$subdivisio = '0';}


        // determina a SEMANA dentro do tempo litúrgico correspondente
        // diminui tempo atual ($nunc) do primeiro dia do tempo litúrgico correspondente.
        // alguns 'primeiros dias' foram forjados como no caso da quaresma pois o primeiro domingo começa
        // apenas depois da quarta-feira de cinzas que, entretanto, já faz parte do tempo litúrgico da quaresma.
        // encontrado o resultado da diferença, divide-se por 60 (segundos), por 60 (minutos), por 24 (horas),
        // por 7 (dias) e teremos a quantidade de semanas entre as datas. Acrescenta-se +1 para em seguida pegar
        // somente a parte inteira (int) desse cálculo. Ter-se-á assim, o número da semana.

        // número da semana do tempo do advento
        $hebdomada_adventus = (int)((($nunc - $dominica_I_adventus)/60/60/24/7)+1);

        // número da semana do tempo do natal
        $hebdomada_nativitatis = (int)((($nunc - $nativitatis)/60/60/24/7)+1);

        // número da semana do tempo comum I
        $hebdomada_per_annum_I = (int)((($nunc - $per_annum_I)/60/60/24/7)+1);

        // TOTAL de semanas do tempo comum I
        $hebdomadae_per_annum_I = (int)((($feria_IV_cinerum - $per_annum_I)/60/60/24/7)+1);

        // número da semana do tempo da quaresma
        $hebdomada_quadragesimae = (int)((($nunc - $dominica_I_in_quadragesima)/60/60/24/7)+1);

        // número da semana do tempo pascal
        $hebdomada_paschae = (int)((($nunc - $pascha)/60/60/24/7)+1);

        // número da semana do tempo comum II
        $hebdomada_per_annum_II = (int)($hebdomadae_per_annum_I+(($nunc - $per_annum_II)/60/60/24/7)+1);

        if        ($tempus == 'adventus') {$hebdomada = $hebdomada_adventus;}
        elseif    ($tempus == 'nativitatis') {$hebdomada = $hebdomada_nativitatis;}
        elseif    ($tempus == 'per_annum' && $nunc < $pascha) {$hebdomada = $hebdomada_per_annum_I;}
        elseif    ($tempus == 'quadragesimae') {$hebdomada = $hebdomada_quadragesimae;}
        elseif    ($tempus == 'paschale') {$hebdomada = $hebdomada_paschae;}
        elseif    ($tempus == 'per_annum' && $nunc > $pascha) {$hebdomada = $hebdomadae_per_annum_II;}


        // determina a SEMANA DO SALTÉRIO
        if      (($hebdomada % 4) == 1) {$hebdomada_psalterii = 1;}
        elseif  (($hebdomada % 4) == 2) {$hebdomada_psalterii = 2;}
        elseif  (($hebdomada % 4) == 3) {$hebdomada_psalterii = 3;}
        else    {$hebdomada_psalterii = 4;}

        // determina se a semana é PAR ou ÍMPAR
        if      (($hebdomada % 2) == 0) {$hebdomada_par_sive_impar = 0;}
        else    {$hebdomada_par_sive_impar = 1;}


        // determina a HORA litúrgica
        // ofício das leituras
        $officium_lectionis = strtotime('00:00:00');

        // laudes
        $laudes = strtotime('05:40:00');

        // terça
        $tertia = strtotime('07:40:00');

        // sexta
        $sexta = strtotime('10:40:00');

        // nona
        $nona = strtotime('13:55:00');

        // vésperas
        $vesperas = strtotime('17:00:00');

        // completas
        $completorium = strtotime('19:25:00');

        if ($officium_lectionis <= $nunc && $nunc < $laudes) {$hora = 'officium_lectionis';}
        elseif ($laudes <= $nunc && $nunc < $tertia) {$hora = 'laudes';}
        elseif ($tertia <= $nunc && $nunc < $sexta) {$hora = 'tertia';}
        elseif ($sexta <= $nunc && $nunc < $nona) {$hora = 'sexta';}
        elseif ($nona <= $nunc && $nunc < $vesperas) {$hora = 'nona';}
        elseif ($vesperas <= $nunc && $nunc < $completorium) {$hora = 'vesperas';}
        else {$hora = 'completorium';}


/*
// importa o conteúdo multilíngue
//  $varietasQuery = "SELECT * FROM lh_varietates WHERE lingua = '$lingua'";
//  $varietasResult = mysql_query($varietasQuery) or die(mysql_error());
//  $varietas = mysql_fetch_array($varietasResult);
    $varietas = ['feria_sexta' => 'Sexta-Feira', 'martius' => 'Março', 'liturgia_horarum' => 'Liturgia das Horas', 'hebdomada' => 'Semana', 'tempus' => 'Quaresma', 'psalterii' => 'Saltério'];



  // determina o dia da semana por extenso para ser impresso no início da página
  if ($dies_hebdomadis == 7) {$N = $varietas['dominica'];}
  elseif ($dies_hebdomadis == 1) {$N = $varietas['feria_secunda'];}
  elseif ($dies_hebdomadis == 2) {$N = $varietas['feria_tertia'];}
  elseif ($dies_hebdomadis == 3) {$N = $varietas['feria_quarta'];}
  elseif ($dies_hebdomadis == 4) {$N = $varietas['feria_quinta'];}
  elseif ($dies_hebdomadis == 5) {$N = $varietas['feria_sexta'];}
  else {$N = $varietas['sabbatum'];}

  if ($mensis == 1) {$F = $varietas['ianuarius'];}
  elseif ($mensis == 2) {$F = $varietas['februarius'];}
  elseif ($mensis == 3) {$F = $varietas['martius'];}
  elseif ($mensis == 4) {$F = $varietas['aprilis'];}
  elseif ($mensis == 5) {$F = $varietas['maius'];}
  elseif ($mensis == 6) {$F = $varietas['iunius'];}
  elseif ($mensis == 7) {$F = $varietas['iulius'];}
  elseif ($mensis == 8) {$F = $varietas['augustus'];}
  elseif ($mensis == 9) {$F = $varietas['september'];}
  elseif ($mensis == 10) {$F = $varietas['october'];}
  elseif ($mensis == 11) {$F = $varietas['november'];}
  else {$F = $varietas['december'];}

  // imprime a data atual e o título do site: Liturgia das Horas
  echo '<h1>',$varietas['liturgia_horarum'],'</h1>';
  echo $N,', ',$dies,' ',$F,' ',$annus,'<br />';
  echo 'Ano ',$cyclus_annualis,', Ciclo ',$ciclus_ferialis,'<br />';
  echo $hebdomada,' ',$varietas['hebdomada'],' ',$varietas["tempus"],'<br />';
  echo $hebdomada_psalterii,' ',$varietas['hebdomada'],' ',$varietas['psalterii'],'<br />';


  /* determina e importa a página de acordo com a hora litúrgica em questão
  if     ($hora == 'laudes')   {$include = 'hrs/horae_maiores/horae_maiores-1.php';}
  elseif ($hora == 'tertia')   {$include = 'hrs/horae_minores/horae_minores-1.php';}
  elseif ($hora == 'sexta')   {$include = 'hrs/horae_minores/horae_minores-1.php';}
  elseif ($hora == 'nona')   {$include = 'hrs/horae_minores/horae_minores-1.php';}
  elseif ($hora == 'vesperas') {$include = 'hrs/horae_maiores/horae_maiores-1.php';}
  else             {$include = "hrs/$hora/$hora-1.php";}
  include ("$include");


echo '$kalendae: ',date('l d F Y H i s', $kalendae),'<br /><br />';
echo '$epiphania: ',date('l d F Y H i s', $epiphania),'<br /><br />';
echo '$baptismus_domini: ',date('l d F Y H i s', $baptismus_domini),'<br /><br />';
echo '$per_annum_I: ',date('l d F Y H i s', $per_annum_I),'<br /><br />';
echo '$dominica_II_per_annum: ',date('l d F Y H i s', $dominica_II_per_annum),'<br /><br />';
echo '$feria_IV_cinerum: ',date('l d F Y H i s', $feria_IV_cinerum),'<br /><br />';
echo '$dominica_I_in_quadragesima: ',date('l d F Y H i s', $dominica_I_in_quadragesima),'<br /><br />';
echo '$dominica_palmarum: ',date('l d F Y H i s', $dominica_palmarum),'<br /><br />';
echo '$pascha: ',date('l d F Y H i s', $pascha),'<br /><br />';
echo '$pentecoste: ',date('l d F Y H i s', $pentecoste),'<br /><br />';
echo '$per_annum_II: ',date('l d F Y H i s', $per_annum_II),'<br /><br />';
echo '$dominica_I_adventus: ',date('l d F Y H i s', $dominica_I_adventus),'<br /><br />';
echo '$dominica_IV_adventus: ',date('l d F Y H i s', $dominica_IV_adventus),'<br /><br />';
echo '$XVII_decembris: ',date('l d F Y H i s', $XVII_decembris),'<br /><br />';
echo '$XXIV_decembris: ',date('l d F Y H i s', $XXIV_decembris),'<br /><br />';
echo '$nativitatis: ',date('l d F Y H i s', $nativitatis),'<br /><br />';
echo '$silvester: ',date('l d F Y H i s', $silvester),'<br /><br />';
echo '$nunc: ',date('l d F Y H i s', $nunc),'<br /><br />';
echo '$cyclus_annualis: ',$cyclus_annualis,'<br /><br />';
echo '$ciclus_ferialis: ',$ciclus_ferialis,'<br /><br />';
echo '$tempus: ',$tempus,'<br /><br />';
echo '$subdivisio: ',$subdivisio,'<br /><br />';
echo '$hebdomada: ',$hebdomada,'<br /><br />';
echo '$hebdomada_psalterii: ',$hebdomada_psalterii,'<br /><br />';
echo '$dies_hebdomadis: ',$dies_hebdomadis,' ',$N,'<br /><br />';
echo '$hora: ',$hora,'<br /><br />';
*/

        return view('index',[
        	'terra' => $terra,
        	'regio' => $regio,
        	'lingua' => $lingua,

        	'utc' => $utc,
			'nunc' => $nunc,
			'annus' => $annus,
			'mensis' => $mensis,
			'dies' => $dies,			        	
			'dies_hebdomadis' => $dies_hebdomadis,

			'kalendae' => $kalendae,
			'epiphania' => $epiphania,
			'baptismus_domini' => $baptismus_domini,

			'per_annum_I' => $per_annum_I,
			'dominica_II_per_annum' => $dominica_II_per_annum,

			'feria_IV_cinerum' => $feria_IV_cinerum,
			'dominica_I_in_quadragesima' => $dominica_I_in_quadragesima,
			'dominica_palmarum' => $dominica_palmarum,

			'pascha' => $pascha,
			'pentecoste' => $pentecoste,

			'per_annum_II' => $per_annum_II,

			'dominica_I_adventus' => $dominica_I_adventus,
			'dominica_IV_adventus' => $dominica_IV_adventus,
			'XVII_decembris' => $XVII_decembris,
			'XXIV_decembris' => $XXIV_decembris,
			'nativitatis' => $nativitatis,
			'silvester' => $silvester,

			'cyclus_annualis' => $cyclus_annualis,
			'ciclus_ferialis' => $ciclus_ferialis,
			'tempus' => $tempus,
			'subdivisio' => $subdivisio,
			'hebdomada' => $hebdomada,
			'hora' => $hora,

    	]);
    }
}

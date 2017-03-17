@extends('layouts.app')
@section('content')

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h1>Liturgia Horarum</h1>
            <p>Terra: {{$terra}}</p>
            <p>Regio: {{$regio}}</p>
            <p>Lingua: {{$lingua}}</p>

            <p>UTC: {{$utc}}</p>
            <p>Nunc: {{$nunc}}</p>
            <p>Annus: {{$annus}}</p>
            <p>Mensis: {{$mensis}}</p>
            <p>Dies: {{$dies}}</p>
            <p>Dies Hebdomadis: {{$dies_hebdomadis}}</p>

            <p>Kalendae: {{$kalendae}}</p>
            <p>Epiphania: {{$epiphania}}</p>
            <p>Baptismus Domini: {{$baptismus_domini}}</p>

            <p>Per Annum I: {{$per_annum_I}}</p>
            <p>Dominica II per Annum: {{$dominica_II_per_annum}}</p>

            <p>Feria IV Cinerum: {{$feria_IV_cinerum}}</p>
            <p>Dominica I in Quadragesima: {{$dominica_I_in_quadragesima}}</p>
            <p>Dominica Palmarum: {{$dominica_palmarum}}</p>

            <p>Pascha: {{$pascha}}</p>
            <p>Pentecoste: {{$pentecoste}}</p>

            <p>Per Annum II: {{$per_annum_II}}</p>

            <p>Dominica I Adventus: {{$dominica_I_adventus}}</p>
            <p>Dominica IV Adventus: {{$dominica_IV_adventus}}</p>
            <p>XVII Decembris: {{$XVII_decembris}}</p>
            <p>XXIV_decembris: {{$XXIV_decembris}}</p>

            <p>Nativitatis: {{$nativitatis}}</p>
            <p>Silvester: {{$silvester}}</p>

            <p>Cyclus Annualis: {{$cyclus_annualis}}</p>
            <p>Cyclus Ferialis: {{$ciclus_ferialis}}</p>
            <p>Tempus: {{$tempus}}</p>
            <p>Subdivisio: {{$subdivisio}}</p>
            <p>Hebdomada: {{$hebdomada}}</p>
            <p>Hora: {{$hora}}</p>

        </div>
    </div>
</div>
@stop

@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{asset('styles/table.css')}}">
@endsection
@section('title')
    Dashboard
@endsection
@section('content')
    <div class="container">
        <div class="header">
            <a href="#" class="logo"><img src="{{asset('images/logo.jpg')}}" alt="ofppt" width="100px"/></a>
            <form action="{{route('logout')}}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary logout">Se déconnecter<img src="./images/exit-svgrepo-com.svg" alt="logout" width="30px"></button>
            </form>
        </div>
        <form class="row" method="get" action="{{route('dashboard')}}">
            <div class="col-xxl-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <select name="filliere" id="filliere" class="form-select">
                    <option selected disabled>Filliere</option>
                    @foreach (App\Models\Appearance::FILLIERES as $filliere => $value)
                        <option value={{$filliere}} {{(request('filliere') ?? old('filliere')) == $filliere ? 'selected' : ''}}>{{$filliere}}</option>
                    @endforeach
                </select>
                @error('filliere')
                    <p class="text-danger mx-2 mt-1"><small>{{$message}}</small></p>
                @enderror
            </div>
            <div class="col-xxl-4 col-xl-3 col-lg-3 col-md-6 col-sm-12">
                <select name="seance" id="seance" class="form-select">
                    <option value="" disabled>-- Séance --</option>
                    @foreach (App\Models\Appearance::SEANCES_STRING as $key => $seance)
                        <option value={{$key}} {{(request('seance') ?? old('seance')) == $key ? 'selected' : ''}}>{{$seance}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-xxl-2 col-xl-3 col-lg-3 col-md-8 col-sm-12">
                <input type="date" class="form-control" name="date" id="date" value="{{request('date') ?? old('date') ?? null}}"/>
                @error('date')
                    <p class="text-danger mx-2 mt-1"><small>{{$message}}</small></p>
                @enderror
            </div>
            <button class="col-xxl-2 col-xl-1 col-lg-1 col-md-4 col-sm-12" type="submit">Afficher</button>
        </form>
        @if ($eleves->isNotEmpty())

            <div class="results">
                <h3>{{App\Models\Appearance::FILLIERES[request('filliere')]}}</h3>
                <h5>{{request('date') ?? null}}</h5>
                <div class="results-container">
                    @foreach ($eleves as $eleve)
                        <div class="single-result">
                            <div class="student">
                                <img width="60px" src="{{asset('images/male.png')}}" alt="male"/>
                                <div>
                                    <h5>{{ $eleve->nom }} {{ $eleve->prenom }}</h5>
                                </div>
                            </div>

                            @if ($eleves_present->contains($eleve))
                                @php
                                    $present = ['success', 'Present'];
                                @endphp
                            @else
                                @php
                                    $present = ['danger', 'Absent'];
                                @endphp
                            @endif

                            <div class="presence">
                                <div id="{{$eleve->id}}" class="testt">
                                    @if ($retard_list->has($eleve->id) && $retard_list[$eleve->id] == true)
                                        <span class="text-warning retard">retard</span>
                                    @endif
                                    <span class="badge bg-{{$present[0]}}">{{$present[1]}}</span>
                                </div>
                                <select class="form-select bg-light select_absence">
                                        <option selected disabled>Modifier</option>
                                        <option value="1">Absent</option>
                                        <option value="2">Présent</option>
                                        <option value="3">Retard</option>
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
@endsection

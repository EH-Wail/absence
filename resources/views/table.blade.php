@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{asset('styles/table.css')}}">
@endsection
@section('title', 'Dashboard')
    
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
    <div class="container">
        <div class="header">
            <a href="#" class="logo"><img src="{{asset('images/logo.jpg')}}" alt="ofppt" width="100px"/></a>
            <form action="{{route('logout')}}" method="post">
                @csrf
                <button type="submit" class="btn btn-outline-secondary logout">Se déconnecter<img src="{{asset('images/exit-svgrepo-com.svg')}}" alt="logout" width="30px"></button>
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
                            
                            @php
                                $present = $eleves_present->contains($eleve) ? ['success', 'Present'] : ['danger', 'Absent'] ;
                            @endphp

                            <div class="presence">

                                <div id="{{$eleve->id}}" class="testt">
                                    @if ($retard_list->contains($eleve->id) )
                                        <span class="text-warning retard">retard</span>
                                    @endif
                                    <span class="badge bg-{{$present[0]}}">{{$present[1]}}</span>
                                </div>
                                <select class="form-select bg-light select_absence" id="statut_absence_{{$eleve->id}}">
                                    <option selected value="" disabled>Choose...</option>
                                    @foreach (App\Models\Appearance::STATUT_ABSENCE as $key => $value)
                                    <option value={{$key}}>{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
    
    <script>
        const select_absence = document.querySelectorAll('.select_absence');
        select_absence.forEach((select) => {
            select.addEventListener('change', () => {
                const selected_value = parseInt(select.value);
                const user = select.previousElementSibling.children;
                if(selected_value === 1){
                    if(user.length === 1){
                        user[0].className = "badge bg-danger";
                        user[0].textContent = "Absent"
                    }else{
                        user[0].style.display = "none";
                        user[1].className = "badge bg-danger";
                        user[1].textContent = "Absent"
                    }
                }
                else if(selected_value === 2){
                    if(user.length === 1){
                        user[0].className = "badge bg-success";
                        user[0].textContent = "Present";
                    }else{
                        user[0].style.display = "none";
                        user[1].className = "badge bg-success";
                        user[1].textContent = "Present";
                    }
                }else if(selected_value === 3){
                    if(user.length === 1){
                        if(user[0].className === "badge bg-success"){
                            const span = document.createElement('span');
                            span.className = "text-warning retard";
                            span.textContent = "retard";
                            user[0].parentNode.insertBefore(span, user[0]);
                        }else if(user[0].className === "badge bg-danger"){
                            user[0].className = "badge bg-success";
                            user[0].textContent = "Present";
                            const span = document.createElement('span');
                            span.className = "text-warning retard";
                            span.textContent = "retard";
                            user[0].parentNode.insertBefore(span, user[0]);
                        }
                    }
                    else{
                        if(user[1].className === "badge bg-success"){
                            user[0].style.display = "block";
                        }else if(user[1].className === "badge bg-danger"){
                            user[1].className = "badge bg-success";
                            user[1].textContent = "Present";
                            user[0].style.display = "block";
                        }
                    }
                }
                const eleveId = select.previousElementSibling.id;
                const date = document.getElementById('date').value;
                const seance = document.getElementById('seance').value;
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const xhr = new XMLHttpRequest();
                let formData = new FormData();
                formData.append('_token', "{{csrf_token()}}")
                formData.append('id', eleveId);
                formData.append('statut_absence', selected_value);
                formData.append('seance', seance);
                formData.append('date', date);
                // 1 => absent , 2 => present 3=> retard
                xhr.open('POST', "{{route('update.ajax')}}");

                xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
                //Dès que la réponse est reçue...
                xhr.onload = function(){
                    //Si le statut HTTP n'est pas 200...
                    if (xhr.status != 200){ 
                        //...On affiche le statut et le message correspondant
                        alert("Erreur " + xhr.status + " : " + xhr.statusText);
                    //Si le statut HTTP est 200, on affiche le nombre d'octets téléchargés et la réponse
                    }else{ 
                        // location.reload();
                        // alert(xhr.response.length + " octets  téléchargés\n" + JSON.stringify(xhr.response));
                    }
                    // console.log(JSON.parse(xhr.response));
                };

                //Si la requête n'a pas pu aboutir...
                xhr.onerror = function(){
                    alert("La requête a échoué");
                };
                xhr.send(formData)
            });
        });
        
    </script>
@endsection
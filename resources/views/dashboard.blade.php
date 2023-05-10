@extends('layout')
@section('title')
    Dashboard
@endsection
@section('content')
<form action="{{route('dashboard')}}" method="get">
  <div class="row">
    <div class="col-3">
      <select class="form-select" name="filliere">
        <option selected disabled>Filliere</option>
        @foreach ($fillieres as $filliere)
          <option value={{$filliere}}>{{$filliere}}</option>
        @endforeach
      </select>
    </div> 
    <div class="col-3">
      <input type="date" name="date" id="date" class="form-control">
    </div>

    <div class="col-3">
      <button class="btn btn-primary">
        Chercher
      </button>
    </div>
    
  </div>
  </form>
@if ($eleves && $eleves->count()>0)
  <table class="table table-sm">
      <thead>
        <tr>
          <th scope="col">Matricule</th>
          <th scope="col">Nom</th>
          <th scope="col">Prénom</th>
          <th scope="col">Présence</th>
        </tr>
      </thead>
      <tbody>
          @foreach ($eleves as $eleve)
              <tr>
                  <th scope="row">{{ $eleve->id }}</th>
                  <td>{{ $eleve->nom }}</td>
                  <td>{{ $eleve->prenom }}</td>
                  <td>
                    @foreach ($eleves_present as $e)
                        @php
                          $present = ['danger', 'Absent'];
                         if($e == $eleve){
                           $present = ['success', 'Present'];
                         }
                        @endphp
                    @endforeach
                    <span class="text-{{$present[0]}}">{{$present[1]}}</span>
                  </td>
              </tr>
          @endforeach
      </tbody>
    </table>
    
@else
  Choississez une filliere et une date 
@endif
</div>
  @endsection

<!DOCTYPE html>
<html>
<head>
    <title>Laravel 9 Import Export Excel to Database Example - ItSolutionStuff.com</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
     
<div class="container">
    <div class="card bg-light mt-3">
        <div class="card-header">
           
        </div>
        <div class="card-body">
            <form action="{{ route('household.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="file" class="form-control">
                <br>
                <button class="btn btn-success">Import FBS Households Data</button>
            </form>
  
            <table class="table table-bordered mt-3">
                <tr>
                    <th colspan="6">
                        List Of Users
                        <a class="btn btn-warning float-end" href="{{ route('household.export') }}">Export User Data</a>
                    </th>
                </tr>
                <tr>
                    <th>Name</th>
                    <th>Community</th>
                    <th>Region</th>
                    <th>Profession</th>
                    <th>Meter Number</th>
                </tr>
                @foreach($energyUsers as $user)
                <tr>
                    <td>{{ $user->english_name }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->region }}</td>
                    <td>{{ $user->profession_name }}</td>
                    <td>{{ $user->meter_number }}</td>
                </tr>
                @endforeach
            </table>
  
        </div>
    </div>
</div>
     
</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>City/Municipality Info</title>
</head>
<body>

    <h1>City / Municipality Information</h1>

    @if ($city)
        <p><strong>City/Municipality Code:</strong> {{ $city->citymunCode }}</p>
        <p><strong>Description:</strong> {{ $city->citymunDescription }}</p>
        <p><strong>Province Code:</strong> {{ $city->provCode }}</p>
        <p><strong>Remarks:</strong> {{ $city->citymunRemarks }}</p>
    @else
        <p>City or Municipality not found.</p>
    @endif

</body>
</html>
<!DOCTYPE html>
<html>
<head>
    <title>Leave Request Accepted as Replacement</title>
</head>
<body>

<p>Bonjour,</p>

<p>Veuillez noter que vous avez été nommé remplaçant.
    Vous remplacez {{ $employee->first_name }} {{ $employee->last_name }}
    @if($fromDate == $toDate)
        le {{ formatDate($fromDate) }}.
    @else
        du {{ formatDate($fromDate) }} au {{ formatDate($toDate) }}.
    @endif
</p>

<strong>Merci</strong>

</body>
</html>

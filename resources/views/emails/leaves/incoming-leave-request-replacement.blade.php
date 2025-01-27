<!DOCTYPE html>
<html>
<head>
    <title>Leave Request Email for Replacement</title>
</head>
<body>

<p>Bonjour,</p>

<p>Veuillez noter que vous avez été affecté en remplacement d'une demande de congé en attente.
    Vous remplacez {{ $employee->first_name }} {{ $employee->last_name }}
    @if($fromDate == $toDate)
        le {{ formatDate($fromDate) }}
    @else
        du {{ formatDate($fromDate) }} au {{ formatDate($toDate) }}.
    @endif
</p>

<strong>Merci</strong>

</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Report</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
        }
        h2 {
            text-align: center;
            color: #3498db;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #3498db;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #3498db;
            color: #fff;
        }
        .logo {
            text-align: center;
            margin-top: 20px;
        }
        .logo img {
            max-width: 150px; /* Adjust the max-width as needed */
        }
    </style>
</head>
<body>
    <div class="logo">
      <!-- <img src="/public/images/tiger.jpg" alt="Tiger Image">-->
     <!-- <img src="{{ asset('images/tiger.jpg') }}" alt="Example Image">-->


    </div>

    <h2>Academic Report</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <!-- Add more columns as needed -->
        </tr>
        <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <!-- Add more columns as needed -->
        </tr>
    </table>
</body>
</html>

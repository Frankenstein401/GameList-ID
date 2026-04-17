<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form id="form">
        <input type="text" id="username" name="username"> <br>
        <input type="password" id="password" name="password"> <br>
        <button type="submit">Kirim</button>
    </form>

    {{-- <script>
        const form = document.getElementById('form');
        form.addEventListener("submit", (e) => {
            e.preventDefault()
            fetch("http://localhost:8000/api/v1/login", {
                    method: "POST",
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        username: document.getElementById('username').value,
                        password: document.getElementById('password').value
                    })
                })
                .then((res) => res.json())
                .then((data) => {
                    localStorage.setItem('accessToken', data.accessToken)
                    console.log(data)
                })
        })
    </script> --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const axios = require ('axios');

        // Make a request for a user with a given ID
        axios.get('https://pokeapi.co/api/v2/pokemon/ditto')
            .then(function(response) {
                console.log(response);
            })
            .catch(function(error) {
                // handle error
                console.log(error);
            })
            .finally(function() {
                // always executed
            });
    </script>
</body>

</html>

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
</body>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>

        // NOTE: Functions
        const handleLogin = (event) => {
            event.preventDefault();
            axios.post(`{{env("APP_URL")}}:{{env("APP_PORT", "8000")}}/api/v1/login`, document.getElementById("form"))
        }


        // NOTE: Event Handler
        document
            .getElementById("form")
            .addEventListener("submit", handleLogin)

</script>

</html>

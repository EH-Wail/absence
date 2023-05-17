<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    @yield('css')
    <title>@yield('title')</title>
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const select_absence = document.querySelectorAll('.select_absence');
        select_absence.forEach((select) => {
            select.addEventListener('change', () => {
                const selected_value = select.value;
                if(parseInt(selected_value) === 1){
                    const user = select.previousElementSibling.children;
                    if(user.length === 1){
                        user[0].className = "badge bg-danger";
                        user[0].textContent = "Absent"
                    }else{
                        user[0].style.display = "none";
                        user[1].className = "badge bg-danger";
                        user[1].textContent = "Absent"
                    }
                }
                else if(parseInt(selected_value) === 2){
                    const user = select.previousElementSibling.children;
                    if(user.length === 1){
                        user[0].className = "badge bg-success";
                        user[0].textContent = "Present";
                    }else{
                        user[0].style.display = "none";
                        user[1].className = "badge bg-success";
                        user[1].textContent = "Present";
                    }
                }else if(parseInt(selected_value) === 3){
                    const user = select.previousElementSibling.children;
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
            });
        });
    </script>
</body>
</html>

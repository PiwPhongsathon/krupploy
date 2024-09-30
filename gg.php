<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SweetAlert2</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <button onclick="showAlert()">Show Alert</button>

    <script>
        function showAlert() {
            Swal.fire({
                title: 'Test Success!',
                text: 'This is a test message.',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            }).then((result) => {
                if (result.isConfirmed) {
                    console.log('Alert closed');
                    // ทำอะไรต่อหลังจากการปิด Alert ถ้าจำเป็น
                }
            });
        }
    </script>
</body>
</html>

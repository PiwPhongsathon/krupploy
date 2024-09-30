function myFunctions() {
    var x = document.getElementById("mynav-links");
    if (x.style.display === "flex") {
        x.style.display = "none";
    } else {
        x.style.display = "flex";
    }
}

window.addEventListener('resize', function () {
    var x = document.getElementById("mynav-links");
    if (window.innerWidth > 768) {
        x.style.display = "flex"; // รีเซ็ตเป็น flex เมื่อหน้าจอใหญ่กว่า 768px
    } else {
        x.style.display = "none"; // ตั้งเป็น none เมื่อหน้าจอเล็กกว่า 768px
    }
});


function openForm() {
    document.getElementById("myForm").style.display = "block";
    document.getElementById("signupfrom").style.display = "none";
}

function closeForm() {
    document.getElementById("myForm").style.display = "none";
}
window.addEventListener('resize', function () {
    var x = document.getElementById("myForm");
    if (window.innerWidth > 768) {
        x.style.display = "block"; // รีเซ็ตเป็น flex เมื่อหน้าจอใหญ่กว่า 768px
    } else {
        x.style.display = "none"; // ตั้งเป็น none เมื่อหน้าจอเล็กกว่า 768px
    }
});



function openForms() {
    document.getElementById("signupfrom").style.display = "block";
    document.getElementById("myForm").style.display = "none";
}

function closeForms() {
    document.getElementById("signupfrom").style.display = "none";
}
window.addEventListener('resize', function () {
    var x = document.getElementById("signupfrom");
    if (window.innerWidth > 768) {
        x.style.display = "none"; // รีเซ็ตเป็น flex เมื่อหน้าจอใหญ่กว่า 768px
    } else {
        x.style.display = "none"; // ตั้งเป็น none เมื่อหน้าจอเล็กกว่า 768px
    }
});


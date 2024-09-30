<?php
session_start();
require_once "../components/database.php";
date_default_timezone_set('Asia/Bangkok');

if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['userid'];

$selected_year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$selected_month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

// ดึงข้อมูลการซื้อคอร์สของนักเรียนที่ครูคนนี้เป็นผู้สอน
$query = "SELECT cp.id, CONCAT(u.fname, ' ', u.lname) AS username, c.course_name, cp.purchase_date, cp.slip_image, cp.status, cp.confirmation_date
          FROM course_purchases cp
          JOIN user u ON cp.student_id = u.id
          JOIN courses c ON cp.course_id = c.id
          WHERE c.user_id = '$user_id' 
          AND ((cp.status = 'รอตรวจสอบ' AND MONTH(cp.purchase_date) = '$selected_month' AND YEAR(cp.purchase_date) = '$selected_year') 
          OR (cp.status = 'ยืนยันแล้ว' AND MONTH(cp.confirmation_date) = '$selected_month' AND YEAR(cp.confirmation_date) = '$selected_year'))
          ORDER BY cp.confirmation_date DESC, cp.purchase_date DESC";


$result = mysqli_query($conn, $query);


// ดึงยอดขายรวมวันนี้
$today_sales_query = "SELECT SUM(c.price) AS today_sales
                      FROM course_purchases cp
                      JOIN courses c ON cp.course_id = c.id
                      WHERE c.user_id = '$user_id' AND cp.status = 'ยืนยันแล้ว'
                      AND DATE(cp.confirmation_date) = CURDATE()";

$today_sales_result = mysqli_query($conn, $today_sales_query);
$today_sales_row = mysqli_fetch_assoc($today_sales_result);
$today_sales = $today_sales_row['today_sales'] ? $today_sales_row['today_sales'] : 0;

// ดึงยอดขายรวมรายเดือน
$monthly_sales_query = "SELECT SUM(c.price) AS monthly_sales
                        FROM course_purchases cp
                        JOIN courses c ON cp.course_id = c.id
                        WHERE c.user_id = '$user_id' AND cp.status = 'ยืนยันแล้ว'
                        AND MONTH(cp.confirmation_date) = '$selected_month'
                        AND YEAR(cp.confirmation_date) = '$selected_year'";

$monthly_sales_result = mysqli_query($conn, $monthly_sales_query);
$monthly_sales_row = mysqli_fetch_assoc($monthly_sales_result);
$monthly_sales = $monthly_sales_row['monthly_sales'] ? $monthly_sales_row['monthly_sales'] : 0;

// ดึงยอดขายรวมรายปี
$yearly_sales_query = "SELECT SUM(c.price) AS yearly_sales
                       FROM course_purchases cp
                       JOIN courses c ON cp.course_id = c.id
                       WHERE c.user_id = '$user_id' AND cp.status = 'ยืนยันแล้ว'
                       AND YEAR(cp.confirmation_date) = YEAR(CURDATE())";

$yearly_sales_result = mysqli_query($conn, $yearly_sales_query);
$yearly_sales_row = mysqli_fetch_assoc($yearly_sales_result);
$yearly_sales = $yearly_sales_row['yearly_sales'] ? $yearly_sales_row['yearly_sales'] : 0;

// ดึงยอดขายรวมทั้งหมด
$total_sales_query = "SELECT SUM(c.price) AS total_sales
                      FROM course_purchases cp
                      JOIN courses c ON cp.course_id = c.id
                      WHERE c.user_id = '$user_id' AND cp.status = 'ยืนยันแล้ว'";

$total_sales_result = mysqli_query($conn, $total_sales_query);
$total_sales_row = mysqli_fetch_assoc($total_sales_result);
$total_sales = $total_sales_row['total_sales'] ? $total_sales_row['total_sales'] : 0;

// ดึงข้อมูลปีที่มีการขายคอร์ส เพื่อแสดงใน dropdown
$years_query = "SELECT DISTINCT YEAR(confirmation_date) AS year 
                FROM course_purchases 
                WHERE confirmation_date IS NOT NULL 
                AND course_id IN (SELECT id FROM courses WHERE user_id = '$user_id') 
                ORDER BY year DESC";
$years_result = mysqli_query($conn, $years_query);

// คำนวณปี พ.ศ. ปัจจุบัน
$current_year_th = date('Y') + 543;
$current_month = date('n');

// ดึงรายชื่อเดือน
$months = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];



// ดึงข้อมูลยอดขายรายเดือนตามปีที่เลือก
$monthly_sales_data_query = "SELECT MONTH(cp.confirmation_date) AS month, SUM(c.price) AS sales
                             FROM course_purchases cp
                             JOIN courses c ON cp.course_id = c.id
                             WHERE c.user_id = '$user_id' 
                             AND cp.status = 'ยืนยันแล้ว'
                             AND YEAR(cp.confirmation_date) = '$selected_year'
                             GROUP BY MONTH(cp.confirmation_date)";

$monthly_sales_data_result = mysqli_query($conn, $monthly_sales_data_query);

if (!$monthly_sales_data_result) {
    die("Query Failed: " . mysqli_error($conn)); // แสดง error ถ้า query ทำงานไม่ถูกต้อง
}

// สร้างอาร์เรย์สำหรับข้อมูลยอดขายรายเดือน (12 เดือน)
$sales_by_month = array_fill(0, 12, 0);

while ($row = mysqli_fetch_assoc($monthly_sales_data_result)) {
    $sales_by_month[$row['month'] - 1] = $row['sales']; // เติมยอดขายในเดือนที่สอดคล้อง
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KruPPloy</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/teacher.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:ital,wght@0,200..1000;1,200..1000&family=Noto+Sans+Thai:wght@100..900&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> <!-- เพิ่ม Chart.js สำหรับสร้างกราฟ -->
</head>

<body>
    <?php include '../components/teacher_header.php'; ?>

    <div class="sidebar">
        <ul>
            <li><a href="#overview"><img src="icon-overview.svg" alt=""> สรุปรายการสินค้า</a></li>
            <li><a href="mycourse.php"><img src="icon-courses.svg" alt=""> คอร์สเรียนของฉัน</a></li>
            <li><a href="mystudent.php"><img src="icon-path.svg" alt=""> นักเรียนของฉัน</a></li>
            <li><a href="myteacher.php"><img src="icon-path.svg" alt=""> คุณครู</a></li>
        </ul>
    </div>

    <div class="containner_program">
        <div class="main-content">
            <h2>สรุปรายการสินค้า</h2>
            <!-- Dropdown สำหรับเลือกปี -->
            <form method="GET" action="" class="d-flex justify-content-start align-items-center">
                <div class="form-group me-2">
                    <label for="year" class="me-2">เลือกปี:</label>
                    <select name="year" id="year" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="" disabled selected><?php echo '--เลือกปี--'; ?></option>
                        <?php while ($year_row = mysqli_fetch_assoc($years_result)): ?>
                            <option value="<?php echo $year_row['year']; ?>" <?php if ($selected_year == $year_row['year']) echo 'selected'; ?>>
                                <?php echo $year_row['year'] + 543; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="month" class="me-2">เลือกเดือน:</label>
                    <select name="month" id="month" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                        <option value="" disabled selected><?php echo '--เลือกเดือน--'; ?></option>
                        <?php foreach ($months as $index => $month): ?>
                            <option value="<?php echo $index + 1; ?>" <?php if ($selected_month == $index + 1) echo 'selected'; ?>>
                                <?php echo $month; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <!-- ======================= Cards ================== -->
            <div class="cardBox">

                <div class="card">
                    <div>
                        <div class="numbers">฿<?php echo number_format($today_sales, 2); ?></div>
                        <div class="cardName">ยอดขายวันนี้</div>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">฿<?php echo number_format($monthly_sales, 2); ?></div>
                        <div class="cardName">ยอดขายรายเดือน (<?php echo $months[$selected_month - 1]; ?>)</div>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">฿<?php echo number_format($yearly_sales, 2); ?></div>
                        <div class="cardName">ยอดขายรายปี (<?php echo $selected_year + 543; ?>)</div>
                    </div>
                </div>

                <div class="card">
                    <div>
                        <div class="numbers">฿<?php echo number_format($total_sales, 2); ?></div>
                        <div class="cardName">ยอดขายรวม</div>
                    </div>
                </div>
            </div>

            <!-- กราฟยอดขายรายเดือน -->
            <h4>ยอดขายรายเดือนสำหรับปี <?php echo $selected_year + 543; ?></h4>
            <canvas id="monthlySalesChart" style="width: 100%; height: 300px;"></canvas>
        </div>




        <!-- ================ Order Details List ================= -->
        <div class="details">
            <div class="recentOrders">
                <div class="cardHeader">
                    <h2>นักเรียนที่ซื้อคอร์ส (เดือน: <?php echo $months[$selected_month - 1]; ?> ปี: <?php echo $selected_year + 543; ?>)</h2>
                    <p>จำนวนคอร์สที่ขายทั้งหมด: <?php echo mysqli_num_rows($result); ?> คอร์ส</p>
                </div>
                <table>
                    <thead>
                        <tr>
                            <td>ชื่อนักเรียน</td>
                            <td>ชื่อคอร์สเรียน</td>
                            <td>วันที่ซื้อ</td>
                            <td>สถานะ</td>
                            <td>วันที่ยืนยัน</td>
                            <td>การจัดการ</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row['username']; ?></td>
                                    <td><?php echo $row['course_name']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($row['purchase_date'])); ?></td>
                                    <td>
                                        <span class="status 
                                <?php
                                if ($row['status'] == 'ยืนยันแล้ว') {
                                    echo 'delivered'; // คลาสที่คุณมีสำหรับ "ยืนยันแล้ว"
                                } elseif ($row['status'] == 'รอตรวจสอบ') {
                                    echo 'pending'; // คลาสที่คุณมีสำหรับ "รอตรวจสอบ"
                                } elseif ($row['status'] == 'หมดอายุแล้ว') {
                                    echo 'return'; // คลาสที่คุณมีสำหรับ "หมดอายุแล้ว"
                                } else {
                                    echo 'inProgress'; // อื่นๆ เช่น กำลังดำเนินการ
                                }
                                ?>">
                                            <?php echo $row['status']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['confirmation_date'] ? date('d/m/Y H:i', strtotime($row['confirmation_date'])) : 'ยังไม่ได้ยืนยัน'; ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <button class="btn-view" onclick="viewSlip('<?php echo $row['slip_image']; ?>')">
                                                <i class="fas fa-eye"></i> ตรวจสอบ
                                            </button>
                                            <?php if ($row['status'] == 'รอตรวจสอบ') { ?>
                                                <button class="btn-confirm" onclick="confirmPurchase(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-check"></i> ยืนยัน
                                                </button>
                                                <button class="btn-delete" onclick="deletePurchase(<?php echo $row['id']; ?>)">
                                                    <i class="fas fa-trash"></i> ลบ
                                                </button>
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align: center;">ยังไม่มีนักเรียนซื้อคอร์สในเดือนนี้</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script src="../js/navbar.js"></script>
        <script src="../js/dropdownmenu.js"></script>

        <!-- สคริปต์สำหรับดูสลิป, ยืนยันการซื้อ และลบคำสั่งซื้อ -->
        <script>
            function viewSlip(slipImage) {
                Swal.fire({
                    title: 'สลิปธนาคาร',
                    imageUrl: '../uploads/slips/' + slipImage,
                    imageWidth: 400,
                    imageHeight: 600,
                    imageAlt: 'Bank Slip',
                    confirmButtonText: 'ปิด'
                });
            }

            function confirmPurchase(purchaseId) {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "การกระทำนี้ไม่สามารถย้อนกลับได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'ยืนยันการซื้อ',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งคำขอ AJAX เพื่ออัปเดตสถานะเป็นยืนยันแล้ว
                        fetch(`../components/confirm_purchase.php?id=${purchaseId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('ยืนยันแล้ว!', 'การซื้อคอร์สได้รับการยืนยันแล้ว.', 'success')
                                        .then(() => {
                                            location.reload();
                                        });
                                } else {
                                    Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถยืนยันการซื้อได้.', 'error');
                                }
                            }).catch(err => {
                                Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถติดต่อเซิร์ฟเวอร์ได้.', 'error');
                            });
                    }
                });
            }

            function deletePurchase(purchaseId) {
                Swal.fire({
                    title: 'คุณแน่ใจหรือไม่?',
                    text: "การกระทำนี้ไม่สามารถย้อนกลับได้!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'ลบคำสั่งซื้อ',
                    cancelButtonText: 'ยกเลิก'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // ส่งคำขอ AJAX เพื่อลบคำสั่งซื้อ
                        fetch(`../components/delete_purchase.php?id=${purchaseId}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire('ลบแล้ว!', 'คำสั่งซื้อคอร์สได้ถูกลบแล้ว.', 'success').then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถลบคำสั่งซื้อได้.', 'error');
                                }
                            });
                    }
                });
            }
        </script>


        <!-- สคริปต์สำหรับกราฟ Chart.js -->
        <script>
            const monthlySalesData = {
                labels: ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'],
                datasets: [{
                    label: 'ยอดขายรายเดือน (บาท)',
                    data: <?php echo json_encode(array_values($sales_by_month)); ?>, // ใส่ข้อมูลยอดขายในแต่ละเดือนจากฐานข้อมูล
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: monthlySalesData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            };

            const monthlySalesChart = new Chart(
                document.getElementById('monthlySalesChart'),
                config
            );
        </script>
</body>

</html>

<?php
$conn->close();
?>
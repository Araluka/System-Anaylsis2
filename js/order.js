// ฟังก์ชันสำหรับตั้งค่าวิธีการชำระเงิน
function setPaymentMethod(method, button) {
    // ตั้งค่าวิธีการชำระเงิน
    document.getElementById('payment-method').value = method;

    // เปลี่ยนสีของปุ่มที่ถูกคลิก
    var buttons = document.querySelectorAll('#payment-form button');
    buttons.forEach(btn => btn.style.backgroundColor = ''); // รีเซ็ตสีของทุกปุ่ม
    button.style.backgroundColor = '#cf5376'; // ตั้งค่าสีของปุ่มที่กด
}

// รอให้ DOM โหลดเสร็จ
document.addEventListener('DOMContentLoaded', function() {
    // การจัดการการคลิกที่ปุ่ม "Order Products"
    document.getElementById('order-button').addEventListener('click', function(e) {
        e.preventDefault(); // ป้องกันการเปลี่ยนเส้นทางไปยัง URL อื่น

        var paymentMethod = document.querySelector('input[name="payment_method"]').value;
        if (!paymentMethod) {
            alert('กรุณาเลือกวิธีการชำระเงิน.');
            return;
        }

        // สร้างฟอร์มใหม่
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'process_order.php'; // กำหนดที่อยู่ของฟอร์ม

        // เพิ่มข้อมูลฟอร์ม
        var product_id = document.createElement('input');
        product_id.type = 'hidden';
        product_id.name = 'product_id';
        product_id.value = '<?php echo $product_id; ?>'; // เปลี่ยนเป็นค่าจริงจาก PHP
        form.appendChild(product_id);

        var quantity = document.createElement('input');
        quantity.type = 'hidden';
        quantity.name = 'quantity';
        quantity.value = '<?php echo $quantity; ?>'; // เปลี่ยนเป็นค่าจริงจาก PHP
        form.appendChild(quantity);

        var payment_method = document.createElement('input');
        payment_method.type = 'hidden';
        payment_method.name = 'payment_method';
        payment_method.value = paymentMethod;
        form.appendChild(payment_method);

        console.log('<?php echo $product_id; ?>'); // ตรวจสอบค่าของ product_id
        console.log('<?php echo $quantity; ?>'); // ตรวจสอบค่าของ quantity

        // เพิ่มฟอร์มไปยังเอกสารและส่งฟอร์ม
        document.body.appendChild(form);
        form.submit(); // ส่งฟอร์ม


    });
});

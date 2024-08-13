// ฟังก์ชันสำหรับตั้งค่าวิธีการชำระเงินและเปลี่ยนสีของปุ่ม
function setPaymentMethod(method, button) {
    // ตั้งค่าวิธีการชำระเงิน
    document.getElementById('payment-method').value = method;

    // เปลี่ยนสีของปุ่มที่ถูกคลิก
    var buttons = document.querySelectorAll('#payment-form button');
    buttons.forEach(btn => btn.style.backgroundColor = ''); // รีเซ็ตสีของทุกปุ่ม
    button.style.backgroundColor = '#cf5376'; // ตั้งค่าสีของปุ่มที่กด
}

document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('order-button').addEventListener('click', function(e) {
        e.preventDefault();
        
        // ตรวจสอบวิธีการชำระเงิน
        var paymentMethod = document.querySelector('input[name="payment_method"]').value;
        if (!paymentMethod) {
            alert('กรุณาเลือกวิธีการชำระเงิน.');
            return;
        }

        // สร้างฟอร์มใหม่เพื่อส่งข้อมูล
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = 'process_order.php';

        // เพิ่มฟิลด์ product_id
        var product_id = document.createElement('input');
        product_id.type = 'hidden';
        product_id.name = 'product_id';
        product_id.value = document.getElementById('product-id').value; // ใช้ค่า product_id ที่เก็บในฟิลด์ hidden
        form.appendChild(product_id);

        // เพิ่มฟิลด์ quantity
        var quantity = document.createElement('input');
        quantity.type = 'hidden';
        quantity.name = 'quantity';
        quantity.value = document.getElementById('quantity').value; // ใช้ค่า quantity ที่เก็บในฟิลด์ hidden
        form.appendChild(quantity);

        // เพิ่มฟิลด์ payment_method
        var payment_method = document.createElement('input');
        payment_method.type = 'hidden';
        payment_method.name = 'payment_method';
        payment_method.value = payment_method;
        form.appendChild(payment_method);

        // ส่งฟอร์ม
        document.body.appendChild(form);
        form.submit();
    });
});

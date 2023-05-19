# Khởi tạo project
- Tải composer và chạy lệnh composer install
- Tạo database bằng file database.sql
- Cài đặt thông tin kết nối database trong src/utils/Database.php
- Chạy lệnh php ./src/utils/generateData.php để tự động tạo các bộ phim, ghế, suất chiếu và đơn hàng mẫu.

(!) Khi chạy xampp cần phải config .htaccess để trỏ vào public/index.php để chạy được. Có thể run bằng built-in php server và chạy bằng lệnh php -S localhost:8000 -t ./public
![Database](movie_booking.png)

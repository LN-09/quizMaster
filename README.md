# 🎯 QuizMaster — Mini LMS Quiz/Practice Test

Hệ thống kiểm tra trắc nghiệm trực tuyến xây dựng bằng **Laravel 11 + MySQL + Blade Template**.

---

## 📋 Tính năng chính

### 👨‍💼 Admin

- Dashboard thống kê: tổng học sinh, quiz, lượt làm bài, tỷ lệ đạt
- **Quản lý Quiz**: tạo, sửa, xóa, xuất bản/ẩn quiz
- **Quản lý Câu hỏi**: thêm/sửa/xóa câu hỏi (single, multiple, true/false)
- **Quản lý Danh mục**: phân loại quiz theo chủ đề + màu sắc
- Cài đặt: giới hạn thời gian, điểm đạt, số lần thi, xáo trộn câu hỏi/đáp án

### 👩‍🎓 Student

- Dashboard cá nhân: thống kê điểm, lượt làm, tỷ lệ đạt
- **Làm bài Quiz**: giao diện làm bài hiện đại với timer đếm ngược
- **Auto-save**: tự động lưu đáp án qua AJAX (không mất dữ liệu khi reload)
- **Kết quả chi tiết**: xem lại từng câu hỏi, đáp án đúng/sai, giải thích
- Lịch sử thi với điểm cao nhất

---

## 🗄️ Database Schema

```
users           — id, name, email, password, role (admin/student)
categories      — id, name, slug, description, color
quizzes         — id, category_id, created_by, title, slug, description,
                  time_limit, passing_score, max_attempts, difficulty,
                  shuffle_questions, shuffle_answers, show_result_immediately, is_published
questions       — id, quiz_id, content, type (single/multiple/true_false),
                  explanation, points, order
answers         — id, question_id, content, is_correct, order
quiz_attempts   — id, quiz_id, user_id, status, score, total_points,
                  earned_points, correct_answers, total_questions, passed,
                  started_at, finished_at, time_spent
attempt_answers — id, quiz_attempt_id, question_id, answer_id, is_correct
```

---

## 🚀 Cài đặt & Chạy

### Yêu cầu

- PHP >= 8.2
- Composer
- MySQL 8+
- Node.js (tuỳ chọn, không dùng Vite)

### Các bước

```bash
# 1. Clone repo
git clone https://github.com/your-username/quizmaster.git
cd quizmaster

# 2. Cài dependencies
composer install

# 3. Tạo file .env
cp .env.example .env
php artisan key:generate

# 4. Cấu hình database trong .env
DB_DATABASE=quizmaster
DB_USERNAME=root
DB_PASSWORD=your_password

# 5. Tạo database
mysql -u root -p -e "CREATE DATABASE quizmaster CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 6. Chạy migration + seed dữ liệu mẫu
php artisan migrate --seed

# 7. Khởi động server
php artisan serve
```

Truy cập: **http://localhost:8000**

### Tài khoản mẫu

| Vai trò | Email                | Mật khẩu |
| ------- | -------------------- | -------- |
| Admin   | admin@quizmaster.com | password |
| Student | an@student.com       | password |
| Student | binh@student.com     | password |

---

## 📁 Cấu trúc thư mục

```
quizmaster/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── QuizController.php
│   │   │   │   ├── QuestionController.php
│   │   │   │   └── CategoryController.php
│   │   │   ├── Student/
│   │   │   │   ├── DashboardController.php
│   │   │   │   ├── QuizController.php
│   │   │   │   └── ResultController.php
│   │   │   └── AuthController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   └── Models/
│       ├── User.php
│       ├── Category.php
│       ├── Quiz.php
│       ├── Question.php
│       ├── Answer.php
│       ├── QuizAttempt.php
│       └── AttemptAnswer.php
├── database/
│   ├── migrations/          # 6 migration files
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── layouts/
│   │   ├── app.blade.php    # Main layout với sidebar
│   │   └── auth.blade.php
│   ├── auth/
│   │   ├── login.blade.php
│   │   └── register.blade.php
│   ├── admin/
│   │   ├── dashboard/
│   │   ├── quizzes/         # CRUD + toggle publish
│   │   ├── questions/       # CRUD câu hỏi
│   │   └── categories/
│   └── student/
│       ├── dashboard.blade.php
│       ├── quizzes/         # Index, show, take
│       └── results/         # Index, show (chi tiết)
├── routes/
│   └── web.php
└── public/
    ├── css/app.css          # Toàn bộ CSS tùy chỉnh
    └── js/
        ├── app.js           # Sidebar + alerts
        ├── quiz-take.js     # Timer, auto-save AJAX
        └── question-form.js # Dynamic answers
```

---

## 🏗️ Kiến trúc MVC

- **Models**: Eloquent ORM với relationships, scopes, và helper methods
- **Controllers**: Tách biệt Admin/Student, validate input, xử lý logic nghiệp vụ
- **Views**: Blade templates với layouts, partials (`_form.blade.php`), sections/stacks
- **Middleware**: `RoleMiddleware` phân quyền admin/student
- **Routes**: Nhóm theo prefix + middleware, resource routes

---

## ✨ Tính năng nổi bật

1. **Auto-save AJAX**: Mỗi khi chọn đáp án, JavaScript gửi request lưu ngay → không mất dữ liệu
2. **Timer thời gian thực**: Đếm ngược, chuyển màu đỏ khi còn 60s, tự nộp khi hết giờ
3. **Soft Deletes**: Quiz xóa không mất dữ liệu lịch sử
4. **Xáo trộn**: Shuffle câu hỏi và đáp án cho mỗi lần thi
5. **Kết quả chi tiết**: Review từng câu, highlight đúng/sai, hiển thị giải thích

---

## 📹 Video Demo

_[Link video demo]_

## 🔗 GitHub Repository

_[Link GitHub repository]_
# quizMaster

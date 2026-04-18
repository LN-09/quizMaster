<?php

namespace Database\Seeders;

use App\Models\Answer;
use App\Models\Category;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─── Users ────────────────────────────────────────────────
        $admin = User::create([
            'name'     => 'Admin QuizMaster',
            'email'    => 'admin@quizmaster.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $student1 = User::create([
            'name'     => 'Nguyễn Văn An',
            'email'    => 'an@student.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        $student2 = User::create([
            'name'     => 'Trần Thị Bình',
            'email'    => 'binh@student.com',
            'password' => Hash::make('password'),
            'role'     => 'student',
        ]);

        // ─── Categories ───────────────────────────────────────────
        $categories = [
            ['name' => 'Lập trình PHP',        'color' => '#8892BE', 'description' => 'Kiến thức lập trình PHP cơ bản và nâng cao'],
            ['name' => 'Laravel Framework',    'color' => '#FF2D20', 'description' => 'Framework PHP phổ biến nhất'],
            ['name' => 'JavaScript',           'color' => '#F0DB4F', 'description' => 'Ngôn ngữ lập trình web phía client'],
            ['name' => 'Cơ sở dữ liệu',       'color' => '#00758F', 'description' => 'SQL, MySQL, database design'],
            ['name' => 'Kiến thức chung CNTT', 'color' => '#6366f1', 'description' => 'Các kiến thức tổng quát về công nghệ thông tin'],
        ];

        $createdCategories = [];
        foreach ($categories as $cat) {
            $createdCategories[] = Category::create($cat);
        }

        // ─── Quizzes & Questions ──────────────────────────────────
        $this->createPhpQuiz($createdCategories[0], $admin);
        $this->createLaravelQuiz($createdCategories[1], $admin);
        $this->createJsQuiz($createdCategories[2], $admin);
        $this->createDbQuiz($createdCategories[3], $admin);
    }

    private function createPhpQuiz(Category $category, User $admin): void
    {
        $quiz = Quiz::create([
            'category_id'             => $category->id,
            'created_by'              => $admin->id,
            'title'                   => 'PHP Cơ Bản - Bài Kiểm Tra',
            'description'             => 'Kiểm tra kiến thức PHP cơ bản bao gồm cú pháp, biến, hàm và OOP.',
            'time_limit'              => 20,
            'passing_score'           => 60,
            'max_attempts'            => 3,
            'difficulty'              => 'easy',
            'shuffle_questions'       => true,
            'shuffle_answers'         => true,
            'show_result_immediately' => true,
            'is_published'            => true,
        ]);

        $questionsData = [
            [
                'content'     => 'PHP là viết tắt của gì?',
                'type'        => 'single',
                'explanation' => 'PHP ban đầu là "Personal Home Page", sau đổi thành "PHP: Hypertext Preprocessor" (đệ quy).',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'Personal Home Page',              'is_correct' => false],
                    ['content' => 'PHP: Hypertext Preprocessor',     'is_correct' => true],
                    ['content' => 'Preprocessed Hypertext Pages',    'is_correct' => false],
                    ['content' => 'Private Hypertext Protocol',      'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Cách khai báo biến trong PHP là gì?',
                'type'        => 'single',
                'explanation' => 'Biến trong PHP bắt đầu bằng dấu $, ví dụ: $name = "Hello";',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'var name = "Hello"',    'is_correct' => false],
                    ['content' => '$name = "Hello"',       'is_correct' => true],
                    ['content' => 'let name = "Hello"',    'is_correct' => false],
                    ['content' => 'string name = "Hello"', 'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Hàm nào dùng để lấy độ dài của một chuỗi trong PHP?',
                'type'        => 'single',
                'explanation' => 'strlen() trả về số byte trong chuỗi. Với Unicode nên dùng mb_strlen().',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'length()',   'is_correct' => false],
                    ['content' => 'count()',    'is_correct' => false],
                    ['content' => 'strlen()',   'is_correct' => true],
                    ['content' => 'size()',     'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Trong PHP, "==" và "===" khác nhau như thế nào?',
                'type'        => 'single',
                'explanation' => '"==" so sánh giá trị (có ép kiểu), "===" so sánh cả giá trị và kiểu dữ liệu.',
                'points'      => 2,
                'answers'     => [
                    ['content' => 'Không có sự khác biệt',                                   'is_correct' => false],
                    ['content' => '"==" so sánh giá trị, "===" so sánh giá trị và kiểu',     'is_correct' => true],
                    ['content' => '"===" nhanh hơn "=="',                                     'is_correct' => false],
                    ['content' => '"==" chỉ dùng cho số nguyên',                              'is_correct' => false],
                ],
            ],
            [
                'content'     => 'PHP là ngôn ngữ phía server (server-side).',
                'type'        => 'true_false',
                'explanation' => 'Đúng. PHP chạy trên server, xử lý request và trả về HTML cho browser.',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'Đúng',  'is_correct' => true],
                    ['content' => 'Sai',   'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $index => $qData) {
            $question = $quiz->questions()->create([
                'content'     => $qData['content'],
                'type'        => $qData['type'],
                'explanation' => $qData['explanation'],
                'points'      => $qData['points'],
                'order'       => $index,
            ]);
            foreach ($qData['answers'] as $aIndex => $aData) {
                $question->answers()->create([
                    'content'    => $aData['content'],
                    'is_correct' => $aData['is_correct'],
                    'order'      => $aIndex,
                ]);
            }
        }
    }

    private function createLaravelQuiz(Category $category, User $admin): void
    {
        $quiz = Quiz::create([
            'category_id'             => $category->id,
            'created_by'              => $admin->id,
            'title'                   => 'Laravel Fundamentals Quiz',
            'description'             => 'Kiểm tra kiến thức về Laravel: routing, Eloquent, Blade, middleware.',
            'time_limit'              => 30,
            'passing_score'           => 70,
            'max_attempts'            => 0,
            'difficulty'              => 'medium',
            'shuffle_questions'       => false,
            'shuffle_answers'         => true,
            'show_result_immediately' => true,
            'is_published'            => true,
        ]);

        $questionsData = [
            [
                'content'     => 'Artisan command nào dùng để tạo một Controller mới?',
                'type'        => 'single',
                'explanation' => 'php artisan make:controller ControllerName tạo controller trong app/Http/Controllers.',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'php artisan create:controller',  'is_correct' => false],
                    ['content' => 'php artisan make:controller',    'is_correct' => true],
                    ['content' => 'php artisan generate:controller','is_correct' => false],
                    ['content' => 'php artisan new:controller',     'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Blade directive nào dùng để kiểm tra xác thực người dùng?',
                'type'        => 'single',
                'explanation' => '@auth kiểm tra user đã đăng nhập, @guest kiểm tra user chưa đăng nhập.',
                'points'      => 1,
                'answers'     => [
                    ['content' => '@isLoggedIn', 'is_correct' => false],
                    ['content' => '@auth',       'is_correct' => true],
                    ['content' => '@user',       'is_correct' => false],
                    ['content' => '@loggedin',   'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Phương thức nào của Eloquent dùng để lấy tất cả bản ghi?',
                'type'        => 'single',
                'explanation' => 'Model::all() trả về Collection chứa tất cả bản ghi trong bảng.',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'Model::get()',      'is_correct' => false],
                    ['content' => 'Model::find()',     'is_correct' => false],
                    ['content' => 'Model::all()',      'is_correct' => true],
                    ['content' => 'Model::select()',   'is_correct' => false],
                ],
            ],
            [
                'content'     => 'File .env trong Laravel dùng để làm gì?',
                'type'        => 'single',
                'explanation' => '.env lưu trữ các biến môi trường như database credentials, API keys không commit lên git.',
                'points'      => 2,
                'answers'     => [
                    ['content' => 'Định nghĩa các route',                             'is_correct' => false],
                    ['content' => 'Cấu hình môi trường (database, mail, cache...)',    'is_correct' => true],
                    ['content' => 'Lưu trữ dữ liệu người dùng',                       'is_correct' => false],
                    ['content' => 'Khai báo các service provider',                    'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Laravel sử dụng mô hình kiến trúc MVC.',
                'type'        => 'true_false',
                'explanation' => 'Đúng. Laravel theo mô hình MVC (Model-View-Controller) giúp tách biệt logic nghiệp vụ.',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'Đúng', 'is_correct' => true],
                    ['content' => 'Sai',  'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $index => $qData) {
            $question = $quiz->questions()->create([
                'content'     => $qData['content'],
                'type'        => $qData['type'],
                'explanation' => $qData['explanation'],
                'points'      => $qData['points'],
                'order'       => $index,
            ]);
            foreach ($qData['answers'] as $aIndex => $aData) {
                $question->answers()->create([
                    'content'    => $aData['content'],
                    'is_correct' => $aData['is_correct'],
                    'order'      => $aIndex,
                ]);
            }
        }
    }

    private function createJsQuiz(Category $category, User $admin): void
    {
        $quiz = Quiz::create([
            'category_id'   => $category->id,
            'created_by'    => $admin->id,
            'title'         => 'JavaScript ES6+ Test',
            'description'   => 'Kiểm tra kiến thức JavaScript hiện đại: arrow functions, promises, destructuring.',
            'time_limit'    => 15,
            'passing_score' => 75,
            'max_attempts'  => 2,
            'difficulty'    => 'medium',
            'shuffle_questions' => true,
            'shuffle_answers'   => true,
            'show_result_immediately' => true,
            'is_published'  => true,
        ]);

        $questionsData = [
            [
                'content'     => 'Arrow function trong JavaScript được ký hiệu bằng cú pháp nào?',
                'type'        => 'single',
                'explanation' => 'Arrow function dùng => thay vì function keyword. Ví dụ: const fn = (x) => x * 2;',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'function() {}',   'is_correct' => false],
                    ['content' => '() => {}',        'is_correct' => true],
                    ['content' => 'fn() {}',         'is_correct' => false],
                    ['content' => 'lambda() {}',     'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Phương thức nào dùng để thêm phần tử vào cuối mảng trong JavaScript?',
                'type'        => 'single',
                'explanation' => 'array.push() thêm một hoặc nhiều phần tử vào cuối mảng và trả về độ dài mới.',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'array.append()',  'is_correct' => false],
                    ['content' => 'array.add()',     'is_correct' => false],
                    ['content' => 'array.push()',    'is_correct' => true],
                    ['content' => 'array.insert()',  'is_correct' => false],
                ],
            ],
            [
                'content'     => 'let và const khác nhau như thế nào?',
                'type'        => 'single',
                'explanation' => 'let cho phép gán lại giá trị, const không cho phép gán lại (nhưng object/array vẫn có thể mutate).',
                'points'      => 2,
                'answers'     => [
                    ['content' => 'Không có sự khác biệt',                                            'is_correct' => false],
                    ['content' => 'const không thể gán lại giá trị, let có thể',                      'is_correct' => true],
                    ['content' => 'let dùng cho số, const dùng cho chuỗi',                             'is_correct' => false],
                    ['content' => 'const có scope rộng hơn let',                                       'is_correct' => false],
                ],
            ],
        ];

        foreach ($questionsData as $index => $qData) {
            $question = $quiz->questions()->create([
                'content'     => $qData['content'],
                'type'        => $qData['type'],
                'explanation' => $qData['explanation'],
                'points'      => $qData['points'],
                'order'       => $index,
            ]);
            foreach ($qData['answers'] as $aIndex => $aData) {
                $question->answers()->create([
                    'content'    => $aData['content'],
                    'is_correct' => $aData['is_correct'],
                    'order'      => $aIndex,
                ]);
            }
        }
    }

    private function createDbQuiz(Category $category, User $admin): void
    {
        $quiz = Quiz::create([
            'category_id'   => $category->id,
            'created_by'    => $admin->id,
            'title'         => 'Cơ Sở Dữ Liệu - SQL Nâng Cao',
            'description'   => 'Kiểm tra kiến thức SQL: JOIN, INDEX, transaction, normalization.',
            'time_limit'    => 25,
            'passing_score' => 70,
            'max_attempts'  => 0,
            'difficulty'    => 'hard',
            'shuffle_questions' => false,
            'shuffle_answers'   => true,
            'show_result_immediately' => true,
            'is_published'  => true,
        ]);

        $questionsData = [
            [
                'content'     => 'INNER JOIN trả về những gì?',
                'type'        => 'single',
                'explanation' => 'INNER JOIN chỉ trả về các hàng có dữ liệu khớp ở CẢ HAI bảng.',
                'points'      => 2,
                'answers'     => [
                    ['content' => 'Tất cả hàng từ bảng trái',                         'is_correct' => false],
                    ['content' => 'Chỉ các hàng có dữ liệu khớp ở cả hai bảng',       'is_correct' => true],
                    ['content' => 'Tất cả hàng từ cả hai bảng',                        'is_correct' => false],
                    ['content' => 'Tất cả hàng từ bảng phải',                          'is_correct' => false],
                ],
            ],
            [
                'content'     => 'Index trong database giúp ích gì?',
                'type'        => 'single',
                'explanation' => 'Index tăng tốc độ truy vấn SELECT nhưng làm chậm INSERT/UPDATE/DELETE và tốn thêm dung lượng.',
                'points'      => 2,
                'answers'     => [
                    ['content' => 'Giảm dung lượng lưu trữ',                    'is_correct' => false],
                    ['content' => 'Tăng tốc độ truy vấn SELECT',                'is_correct' => true],
                    ['content' => 'Tăng tốc độ INSERT và UPDATE',               'is_correct' => false],
                    ['content' => 'Bảo mật dữ liệu',                            'is_correct' => false],
                ],
            ],
            [
                'content'     => 'PRIMARY KEY có thể chứa giá trị NULL.',
                'type'        => 'true_false',
                'explanation' => 'Sai. PRIMARY KEY luôn NOT NULL và UNIQUE. Không được phép chứa NULL.',
                'points'      => 1,
                'answers'     => [
                    ['content' => 'Đúng', 'is_correct' => false],
                    ['content' => 'Sai',  'is_correct' => true],
                ],
            ],
        ];

        foreach ($questionsData as $index => $qData) {
            $question = $quiz->questions()->create([
                'content'     => $qData['content'],
                'type'        => $qData['type'],
                'explanation' => $qData['explanation'],
                'points'      => $qData['points'],
                'order'       => $index,
            ]);
            foreach ($qData['answers'] as $aIndex => $aData) {
                $question->answers()->create([
                    'content'    => $aData['content'],
                    'is_correct' => $aData['is_correct'],
                    'order'      => $aIndex,
                ]);
            }
        }
    }
}

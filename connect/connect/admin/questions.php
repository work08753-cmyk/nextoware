<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_admin();

$success = '';

// Handle Add Question
if (isset($_POST['add_question'])) {
    $q = $_POST['question'];
    $oa = $_POST['option_a'];
    $ob = $_POST['option_b'];
    $oc = $_POST['option_c'];
    $od = $_POST['option_d'];
    $correct = $_POST['correct_option'];

    $stmt = $pdo->prepare("INSERT INTO assessment_questions (question, option_a, option_b, option_c, option_d, correct_option) VALUES (?, ?, ?, ?, ?, ?)");
    if ($stmt->execute([$q, $oa, $ob, $oc, $od, $correct])) {
        $success = "Question added successfully!";
    }
}

// Handle Delete Question
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM assessment_questions WHERE id = ?");
    if ($stmt->execute([$id])) {
        $success = "Question deleted successfully!";
    }
}

$questions = $pdo->query("SELECT * FROM assessment_questions ORDER BY created_at DESC")->fetchAll();

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manage MCQs</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
            <i class="bi bi-plus-lg me-2"></i> Add Question
        </button>
    </div>

    <?php if ($success): ?>
        <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php endif; ?>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        <?php foreach($questions as $index => $q): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0 question-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-primary fw-bold">Q<?php echo $index + 1; ?></h6>
                            <a href="?delete=<?php echo $q['id']; ?>" class="text-danger" onclick="return confirm('Delete this question?')"><i class="bi bi-trash"></i></a>
                        </div>
                        <p class="mb-3 fw-bold"><?php echo $q['question']; ?></p>
                        <ul class="list-unstyled mb-0">
                            <li class="<?php echo $q['correct_option'] == 'A' ? 'text-success fw-bold' : ''; ?>">A: <?php echo $q['option_a']; ?></li>
                            <li class="<?php echo $q['correct_option'] == 'B' ? 'text-success fw-bold' : ''; ?>">B: <?php echo $q['option_b']; ?></li>
                            <li class="<?php echo $q['correct_option'] == 'C' ? 'text-success fw-bold' : ''; ?>">C: <?php echo $q['option_c']; ?></li>
                            <li class="<?php echo $q['correct_option'] == 'D' ? 'text-success fw-bold' : ''; ?>">D: <?php echo $q['option_d']; ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Add MCQ</h5><button type="button" class="btn-close" data-bs-toggle="modal" data-bs-target="#addQuestionModal"></button></div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3"><label class="form-label">Question Text</label><textarea name="question" class="form-control" required></textarea></div>
                    <div class="mb-2"><label class="form-label small">Option A</label><input type="text" name="option_a" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label small">Option B</label><input type="text" name="option_b" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label small">Option C</label><input type="text" name="option_c" class="form-control" required></div>
                    <div class="mb-2"><label class="form-label small">Option D</label><input type="text" name="option_d" class="form-control" required></div>
                    <div class="mb-3">
                        <label class="form-label">Correct Option</label>
                        <select name="correct_option" class="form-select" required>
                            <option value="A">Option A</option>
                            <option value="B">Option B</option>
                            <option value="C">Option C</option>
                            <option value="D">Option D</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer"><button type="submit" name="add_question" class="btn btn-primary">Save Question</button></div>
            </form>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

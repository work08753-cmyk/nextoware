<?php
require_once '../config/db.php';
require_once '../includes/auth_check.php';
check_engineer();

$engineer_id = $_SESSION['engineer_id'];

// Check if already given
$stmt = $pdo->prepare("SELECT has_given_assessment, status, engineering_domain FROM engineers WHERE id = ?");
$stmt->execute([$engineer_id]);
$eng = $stmt->fetch();

if ($eng['status'] != 'Approved') {
    die("Wait for admin approval before taking the assessment.");
}
if ($eng['has_given_assessment']) {
    header("Location: dashboard.php");
    exit();
}

// Fetch questions based on domain
$domain = $eng['engineering_domain'] ?? 'Both';

if ($domain === 'Both') {
    // If Both, maybe fetch 5 from each or all? User: "merge the quiz". Let's fetch 10 mixed or all.
    // User asked: "merge the quiz... merge services... and quiz will be last".
    // "put 3 options... mix... merge the quiz".
    // I will fetch 10 random questions from ANY category (or specific mix).
    // Let's do 5 Mechanical and 5 Hardware to cover both if possible, or just 10 random from all.
    // Simplest: SELECT * FROM assessment_questions ORDER BY RAND() LIMIT 20 (Wait user said 10 MCQs each section).
    // If it's a mix, let's give them 20 questions (10 mech + 10 hardware) or 10 mixed. The user prompt lists 10+10.
    // "add this quize... at mix side merge the quiz". Implies full set? Or combined set.
    // Let's assume the user wants the engineer to take the relevant quiz. If mix, they take both parts (20 Qs).
    $questions = $pdo->query("SELECT * FROM assessment_questions ORDER BY category, id")->fetchAll();
} else {
    $stmt = $pdo->prepare("SELECT * FROM assessment_questions WHERE category = ? ORDER BY RAND() LIMIT 10");
    $stmt->execute([$domain]);
    $questions = $stmt->fetchAll();
}

if (empty($questions) && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    $error = "Assessment system not ready. (Admin needs to add questions for $domain)";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answers = $_POST['answers']; // array of question_id => answer
    $score = 0;
    $total = count($answers);

    $pdo->beginTransaction();
    try {
        foreach ($answers as $q_id => $ans) {
            $stmt = $pdo->prepare("SELECT correct_option FROM assessment_questions WHERE id = ?");
            $stmt->execute([$q_id]);
            $correct = $stmt->fetchColumn();
            
            $is_correct = ($ans === $correct) ? 1 : 0;
            if ($is_correct) $score++;

            $stmt = $pdo->prepare("INSERT INTO assessment_results (engineer_id, question_id, selected_answer, is_correct) VALUES (?, ?, ?, ?)");
            $stmt->execute([$engineer_id, $q_id, $ans, $is_correct]);
        }

        $final_score = ($score / $total) * 100;
        $stmt = $pdo->prepare("UPDATE engineers SET has_given_assessment = 1, assessment_score = ? WHERE id = ?");
        $stmt->execute([$final_score, $engineer_id]);
        
        $pdo->commit();
        $success = "Assessment completed! Your score: " . round($final_score, 1) . "%";
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = "Error saving results: " . $e->getMessage();
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4">
                <h2 class="fw-bold mb-2">Technical Assessment</h2>
                <p class="text-secondary mb-4">Proof your hardware engineering skills. You have one attempt at this 10-question MCQ.</p>

                <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                <?php elseif (isset($success)): ?>
                    <div class="alert alert-success h4 text-center py-4"><?php echo $success; ?></div>
                    <div class="text-center"><a href="dashboard.php" class="btn btn-primary px-5">Go to Dashboard</a></div>
                <?php else: ?>
                    <form method="POST" id="assessmentForm">
                        <?php foreach($questions as $idx => $q): ?>
                            <div class="mb-5 question-block">
                                <h5 class="fw-bold mb-3"><?php echo ($idx + 1) . ". " . $q['question']; ?></h5>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" value="A" id="q<?php echo $q['id']; ?>A" required>
                                    <label class="form-check-label" for="q<?php echo $q['id']; ?>A"><?php echo $q['option_a']; ?></label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" value="B" id="q<?php echo $q['id']; ?>B">
                                    <label class="form-check-label" for="q<?php echo $q['id']; ?>B"><?php echo $q['option_b']; ?></label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" value="C" id="q<?php echo $q['id']; ?>C">
                                    <label class="form-check-label" for="q<?php echo $q['id']; ?>C"><?php echo $q['option_c']; ?></label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="answers[<?php echo $q['id']; ?>]" value="D" id="q<?php echo $q['id']; ?>D">
                                    <label class="form-check-label" for="q<?php echo $q['id']; ?>D"><?php echo $q['option_d']; ?></label>
                                </div>
                            </div>
                        <?php endforeach; ?>
                        <hr class="my-4">
                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 shadow">Submit Assessment</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>

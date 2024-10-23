<?php
$pageName = "Quiz";
// include header
include 'assets/templates/quiz/header.php';

//test
$pageName = "Quiz";

// check if learner is logged in, if not, redirect to login page
if (!isLearnerLoggedIn()) {
  jsRedirect(SITE_URL . "quizzes-login");
}

// check if learner is in the middle of a question (to prevent timer reset on refreshing)
if ($_SESSION["questionStartedTime"] == "") {
  $_SESSION["questionStartedTime"] = strtotime(date('Y-m-d H:i:s'));
}

//GET current Time to prevent user from refreshing.
$timeNow = strtotime(date('Y-m-d H:i:s'));

//Initialize variables 
$currentQuestion = $_SESSION["currentQn"] + 1;
$score = $_SESSION["currentScore"];
$totalQns = $_SESSION["numOfQns"];
$scoreFrac = $score / $totalQns;
$scorePercent = $scoreFrac * 100;
?>

<!-- StudentQuizStatus
0 = Started but Incomplete
1 = Started but Time's Up
2 = Started and Completed -->

<!---------- Main Content ---------->
<!---------- Custom CSS ---------->

<body id="body-quiz-question">

  <div class="quiz-bg"></div>
  <div id="mainWrapper" class="wrapper position-relative">

    <!------------------------- Modal ----------------------------->

    <!----------  Modal content (Correct) ---------->
    <div class="modal fade correct-modal text-center" tabindex="1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered text-center">
        <div class="modal-content py-2 px-2">
          <div class="d-flex justify-content-between align-items-center py-4 px-4">
            <h3 class="modal-title mt-0 text-center" id="myLargeModalLabel">You got it correct!</h3>
            <button type="button" class="btn-close my-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <hr class="mx-4 my-0 line">
          <?php
          $correctQuery = DB::query("SELECT questionCorrectAnswerTitle, questionCorrectAnswerImage, questionCorrectAnswerDescription FROM question WHERE questionID=%i", $_SESSION["questionArray"][$_SESSION["currentQn"]]);
          foreach ($correctQuery as $correctResult) {
            $correctTitle = $correctResult["questionCorrectAnswerTitle"];
            $correctImage = $correctResult["questionCorrectAnswerImage"];
            $correctDescription = $correctResult["questionCorrectAnswerDescription"];
          }
          ?>
          <div class="modal-body px-4 py-4">
            <?php
            if (isset($correctImage)) {
            ?>
              <img class="text-center img-answer my-3" src="assets/images/question/<?php echo $correctImage ?>" alt="Correct Answer Image">
            <?php
            }
            ?>
            <h4 class="my-3"><?php echo $correctTitle ?></h4>
            <p class="my-3"><?php echo $correctDescription ?></p>

            <button type="button" class="btn btn-primary my-4" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!----------  Modal content (Wrong) ---------->
    <div class="modal fade wrong-modal text-center" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content py-2 px-2">
          <div class="d-flex justify-content-between align-items-center py-4 px-4">
            <h3 class="modal-title mt-0" id="myLargeModalLabel">You got it wrong!</h3>
            <button type="button" class="btn-close my-auto" data-bs-dismiss="modal" aria-hidden="true"></button>
          </div>
          <hr class="mx-4 my-0 line">
          <?php
          $wrongQuery = DB::query("SELECT questionWrongAnswerTitle, questionWrongAnswerImage, questionWrongAnswerDescription FROM question WHERE questionID=%i", $_SESSION["questionArray"][$_SESSION["currentQn"]]);
          foreach ($wrongQuery as $wrongResult) {
            $wrongTitle = $wrongResult["questionWrongAnswerTitle"];
            $wrongImage = $wrongResult["questionWrongAnswerImage"];
            $wrongDescription = $wrongResult["questionWrongAnswerDescription"];
          }
          ?>
          <div class="modal-body px-4 py-4">
            <?php
            if (isset($wrongImage)) {
            ?>
              <img class="text-center img-answer my-3" src="assets/images/question/<?php echo $wrongImage ?>" alt="Wrong Answer Image">
            <?php
            }
            ?>
            <h4 class="mt-3"><?php echo $wrongTitle ?></h4>
            <p class="mt-3 mb-3"><?php echo $wrongDescription ?></p>

            <button type="button" class="btn btn-primary my-4" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
    <div class="container p-0">

      <!------------------------- Summary (End of Quiz Message)----------------------------->

      <div class="col-12 m-auto">
        <?php
        if ($_SESSION["remainingQns"] == 0) {
        ?>
          <section id="score-message">
            <div class="col-12 mx-auto align-self-center">
              <div class="card card-body-container mx-4 text-center">
                <div class="row">
                  <?php
                  // User passed quiz
                  if ($scorePercent >= 50) {
                  ?>
                    <div class="col-10 card-body text-center mx-5 mt-4">
                      <img src="assets/images/quiz/quiz-playthrough/congratulate.png" class="score-msg-img">
                    </div>
                    <div class="col-10 card-body mx-auto">
                      <h2 class="mb-2">Congratulations!</h2>
                      <h6 class="score-msg">Well Done! You have passed the Quiz!</h5>
                        <h5 class="mt-5">You scored</h5>
                        <p class="pass-quiz quiz-score mt-1"><?php echo round($scorePercent) ?>%</p>
                    </div>
                  <?php
                  } else {
                    //User failed quiz
                  ?>
                    <div class="col-10 card-body text-center mx-5 mt-4">
                      <img src="assets/images/quiz/quiz-playthrough/try-again.png" class="score-msg-img">
                    </div>
                    <div class="col-10 card-body mx-5">
                      <h2 class="mb-2">Oh No...</h2>
                      <h6 class="score-msg">Would you like to try again?</h6>
                      <h5 class="mt-5">You scored</h5>
                      <p class="fail-quiz quiz-score mt-1"><?php echo round($scorePercent) ?>%</p>
                    </div>
                  <?php
                  }
                  ?>
                  <div class="col-lg-10 col-md-10 col-sm-10 card-body mt-3 mb-4 text-center mx-5">
                    <h4 class="mb-4">Try again?</h4>
                    <div class="mx-auto">
                      <button class="btn btn-primary mb-4 mx-2" onclick="location.replace(`<?php echo SITE_URL; ?>quizzes-quiz-info`)">Yes</button>
                      <button class="btn btn-secondary mb-4 mx-2" onclick="location.replace(`<?php echo SITE_URL . 'quizzes-logout'; ?>`)">No</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        <?php
        } else {
        ?>
          <!------------------------- Calling out next Question ----------------------------->
          <section>
            <div id="replace">
              <?php
              $questionQuery = DB::query("SELECT * FROM question WHERE questionID=%i", $_SESSION["questionArray"][$_SESSION["currentQn"]]);
              foreach ($questionQuery as $questionResult) {
                $questionTitle = $questionResult["questionTitle"];
                $questionImage = $questionResult["questionImage"];
                $questionType = $questionResult["questionType"];
                $questionTime = $questionResult["questionTime"];
              }
              ?>
              <div class="card card-body-container card-question question_title py-4 px-4 mb-4 text-center mx-2">
                <div class="row d-flex justfiy-content-center align-items-center px-2">
                  <div class="question_score col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4 mb-3 text-uppercase text-start animate__animated animate__pulse animate__infinite animate__slow animate__delay-5s">
                    <!---------- Score Display ---------->
                    <span id="questionScore">
                      <i class="fa-solid fa-star mr-5"></i>
                      <h6><?php echo $score ?></h6>
                    </span>
                  </div>

                  <!---------- Timer Display ---------->
                  <div class="timer col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4 mb-4"></div>

                  <!---------- Question Number Display ---------->
                  <div class="question_number col-lg-4 col-md-4 col-sm-4 col-xs-4 col-4 mb-3 text-uppercase text-end">
                    <span id="questionNo">
                      <i class="fa-solid fa-circle-question mr-5"></i>
                      <h6><?php echo $currentQuestion ?> / <?php echo $_SESSION["numOfQns"] ?></h6>
                    </span>
                  </div>
                </div>

                <!---------- Subject Display ---------->
                <h6 class="text-uppercase subject-name mb-2"><?php echo $_SESSION["quizCategoryName"]; ?></h6>

                <!---------- Question Display ---------->
                <?php
                if (isset($questionImage)) {
                ?>
                  <div class="question-image mt-2 mb-3 mx-auto">
                    <img src="assets/images/question/<?php echo $questionImage ?>">
                  </div>
                <?php
                }
                ?>
                <h4 class="my-2"><?php echo $questionTitle ?></h4>
                <p id="questionInstruction"><?php if ($questionType == 2) {
                                              echo "Choose all correct answers.";
                                            } else {
                                              echo "Choose one correct answer.";
                                            } ?></p>
                <input type="hidden" id="questionType" name="questionType" value="<?php echo $questionType ?>">
                <input id="questionTime" hidden style="display:hidden;" type="number" value="<?php echo $questionTime; ?>">
              </div>

              <!---------- Query the Options that are linked to the Question Type ---------->
              <div class="row d-flex flex-row justify-content-between mx-0">
                <?php
                $i = 1;

                //Filter if question type is 4
                if ($questionType == 4) {
                  echo '<div class="form_items col-lg-12 col-md-12 col-sm-12 col-xs-12 mt-2 mx-0 px-2 row d-flex flex-row justify-content-center text-center">';
                };

                $optionQuery = DB::query("SELECT DISTINCT * FROM `option` WHERE questionID=%i ORDER BY RAND ()", $_SESSION["questionArray"][$_SESSION["currentQn"]]);
                foreach ($optionQuery as $optionResult) {
                  $optionID = $optionResult["optionID"];
                  $optionText = $optionResult["optionText"];
                  $optionCorrect = $optionResult["optionCorrect"];
                ?>
                  <!---------- Options for question 4 ---------->
                  <?php if ($questionType == 4) { ?>
                    <label for="opt-<?php echo $i; ?>" class="labelQuestionType4 step-<?php echo $i; ?> text-center mx-4 my-1 d-flex flex-column align-items-center">
                      <span class="position-absolute"></span>
                      <?php echo '<img src="assets/images/question/' . $optionText . '" class="image-option">'; ?>
                      <input id="opt-<?php echo $i ?>" class="step option" name="stp-<?php echo $i ?>_select_option" value="<?php echo $optionCorrect ?>">
                    </label>
                  <?php
                  } else {
                  ?>
                    <!---------- Options for question 1-3 ---------->
                    <div class="form_items mt-2 col-lg-6 col-md-12 col-sm-12 px-2 text-center">
                      <label for="opt-<?php echo $i ?>" class="step-<?php echo $i; ?> quiz-option text-center rounded-pill position-relative bg-white">
                        <span class="circle position-absolute"></span>
                        <h6>
                          <?php echo $optionText; ?>
                        </h6>
                        <input id="opt-<?php echo $i ?>" class="step option" name="stp-<?php echo $i ?>_select_option" value="<?php echo $optionCorrect ?>">
                      </label>
                    </div>
                <?php
                  }
                  $i++;
                }
                if ($questionType == 4) {
                  echo '</div>';
                }
                ?>

              </div>

              <!---------- Submit button ---------->
              <div class="col-lg-10 col-md-10 col-sm-5 col-xs-5 card-body mt-3 mb-4 text-center mx-auto">
                <div class="mx-auto">
                  <button type="submit" class="btn btn-primary next_btn position-relative rounded-pill hide  mx-2 mb-4" id="submit-btn">Submit</button>
                  <button type="submit" class="btn btn-primary next_btn position-relative rounded-pill hide mb-4 mx-2" id="next-btn" onclick="location.replace('?site=quizzes-quiz')">
                    <?php
                    if ($_SESSION["remainingQns"] == 1) {
                      echo "End Quiz";
                    } else {
                      echo "Next Question";
                    }
                    ?>
                  </button>
                  <button type="submit" class="btn btn-secondary next_btn position-relative rounded-pill hide  mx-2 mb-4" id="explanation-btn">Explanation</button>
                </div>
              </div>
            </div>
          </section>
        <?php
        };
        ?>
      </div>
    </div>
  </div>

  <!---------- Footer Include ---------->
  <?php
  include 'assets/templates/quiz/footer.php';
  ?>

  <script>
    $(document).ready(function() {
      function load_data(query) {
        $.ajax({
          url: "<?php echo SITE_URL . 'quizzes-nextQuestion' ?>",
          method: "POST",
          data: {
            query: query
          },
          success: function(data) {}
        });
      }

      // Question Type 1 = Multiple Choice (1 answer)
      // Question Type 2 = Multiple Choice (Multi Answer)
      // Question Type 3 = True/False (1 answer)
      // Question Type 4 = Image Answer (1 answer)

      //Highlight Selected Options & Show Submit Button
      $('.option').click(function() {
        // for questionType all except multiAnswer
        if ($('#questionType').val() == 1 || $('#questionType').val() == 3 || $('#questionType').val() == 4) {
          $('#submit-btn').removeClass('hide');
          $('.option').parent().removeClass('active');
          $('.option').parent().removeClass('text-white');
          $('.option').removeClass('selected');
          $(this).addClass('selected');
          $(this).parent().addClass('active');
          $(this).parent().addClass('text-white');
          if (!$('.option').parent().hasClass('active')) {
            $('#submit-btn').addClass('hide');
          }
          // scrollIntoView to reveal the submit button
          var elmntToView = document.getElementById("submit-btn");
          elmntToView.scrollIntoView({
            behavior: "smooth",
            block: "end",
            inline: "nearest"
          });
          // for questionType multiAnswer
        } else if ($('#questionType').val() == 2) {
          $('#submit-btn').removeClass('hide');
          $(this).toggleClass('selected');
          $(this).parent().toggleClass('active');
          $(this).parent().toggleClass('text-white');
          if ($(this).hasClass('selected')) {
            $(this).parent().children('span').append('<i class="fa-2x bi bi-check-circle"></i>');
          } else {
            $(this).parent().children('span').children().remove();
          }
          if (!$('.option').parent().hasClass('active')) {
            $('#submit-btn').addClass('hide');
          }
        }
      });

      //When submit button is clicked
      // Check Answer
      $('#submit-btn').click(function(event) {
        event.preventDefault();
        $('#submit-btn').addClass('hide');
        $('#next-btn').removeClass('hide');

        // Set time to 
        timeLimit = Infinity;
        $('.timer').css('visibility', 'hidden');

        //Changes to be applied to each option upon submit button
        //Initialize sumOfCorrect 
        var sumOfCorrect = 0;
        $('.option').each(function() {
          $(this).attr('disabled', true);
          if ($(this).val() == 1) {

            //Totalling up the values of all 4 options from DB
            sumOfCorrect += parseFloat(this.value);
            $(this).parent().removeClass("active");
            $(this).parent().removeClass("bg-white");
            $(this).parent().removeClass("text-white");
            $(this).parent().addClass("correctOptions");
            $(this).parent().addClass("text-white");
          }
        });

        //Changes to be applied to each selected option
        //Initialize sumOfSelected
        var sumOfSelected = 0;
        $(".selected").each(function() {

          //Sum up the values of the selected options only
          sumOfSelected += parseFloat(this.value);
          if ($(this).val() == 0) {
            $(this).parent().removeClass("active");
            $(this).parent().removeClass("bg-white");
            $(this).parent().addClass("wrongOptions");

            //Multiple answers question 
            if ($('#questionType').val() == 2) {
              $(this).parent().children('span').children().remove();
              $(this).parent().children('span').append('<i class="fa-2x bi bi-x-circle"></i>');
            }
          }
        });

        //Explanation button
        $('#explanation-btn').removeClass('hide');
        // scrollIntoView the explanation button
        var elmntToView = document.getElementById("explanation-btn");
        elmntToView.scrollIntoView({
          block: "end",
          inline: "nearest"
        });
        console.log(sumOfSelected);
        //Check if the user has selected correct options and NO wrong options and send result via AJAX POST method
        if (sumOfSelected == sumOfCorrect && !$('.option').parent().hasClass('wrongOptions')) {
          load_data(1);
          $('.correct-modal').modal('show');
          var correct = 1;
          $('#questionScore h6').html(<?php echo $score + 1 ?>);
        } else {
          load_data(0);
          $('.wrong-modal').modal('show');
          var correct = 0;
        }
        $('#explanation-btn').click(function() {
          if (correct == 1) {
            $('.correct-modal').modal('show');
          } else if (correct == 0) {
            $('.wrong-modal').modal('show');
          }
        });
      });

      //Timer JS
      var width = 65,
        height = 65,
        timePassed = <?php echo ($timeNow - $_SESSION["questionStartedTime"]); ?>,
        timeLimit = <?php echo $questionTime; ?>;

      var fields = [{
        value: timeLimit,
        size: timeLimit,
        update: function() {
          return timePassed = timePassed + 1;
        }
      }];

      var nilArc = d3.svg.arc()
        .innerRadius(width / 1 - 133)
        .outerRadius(width / 2 - 133)
        .startAngle(0)
        .endAngle(2 * Math.PI);

      var arc = d3.svg.arc()
        .innerRadius(width / 1 - 40)
        .outerRadius(width / 1 - 45)
        .startAngle(0)
        .endAngle(function(d) {
          return ((d.value / d.size) * 2 * Math.PI);
        });

      var svg = d3.select(".timer").append("svg")
        .attr("width", width)
        .attr("height", height);

      var field = svg.selectAll(".field")
        .data(fields)
        .enter().append("g")
        .attr("transform", "translate(" + width / 2 + "," + height / 2 + ")")
        .attr("class", "field");

      var back = field.append("path")
        .attr("class", "path path--background")
        .attr("d", arc);

      var path = field.append("path")
        .attr("class", "path path--foreground");

      var label = field.append("text")
        .attr("class", "label")
        .attr("dy", ".35em");

      (function update() {
        field
          .each(function(d) {
            d.previous = d.value, d.value = d.update(timePassed);
          });
        path.transition()
          .ease("elastic")
          .duration(500)
          .attrTween("d", arcTween);

        if ((timeLimit - timePassed) <= 5) {
          pulseText();

        } else {
          label
            .text(function(d) {
              return d.size - d.value;
            });
        }

        if (timePassed < timeLimit) {
          setTimeout(update, 1000 - (timePassed % 1000));
        } else if ((timeLimit - timePassed) <= 0) {
          load_data(9);
          $('.option').each(function() {
            $(this).attr('disabled', true);
          });
          $('#submit-btn').remove();
          $('#next-btn').remove();
          $("#question").after("<span> time is up</span>");
          Swal.fire({
            title: 'Game Over',
            text: 'Time Is Up',
            icon: 'warning',
            confirmButtonColor: '#63d297',
            confirmButtonText: 'OK',
          }).then(function() {
            timeLimit = Infinity;
            $('.timer').css('visibility', 'hidden');
            // Times Up HTML
            $('#replace').replaceWith(`
                        <div class="row d-flex justify-content-center">
                            <div class="col-12 mx-auto align-self-center">
                                <div class="card card-body-container mx-4 text-center">
                                    <div class="row">
                                        <div class="col-10 card-body text-center mx-5 mt-4">
                                            <img src="assets/images/quiz/quiz-playthrough/time-up.png" class="score-msg-img">
                                        </div>
                                        <div class="col-10 card-body mx-5">
                                            <h2 class="mb-2">Time is up!</h2>
                                            <h6 class="score-msg">You did not complete the question on time!</h6>
                                            <h5 class="mt-5">You scored</h5>
                                            <p class="<?php if ($scorePercent >= 50) {
                                                        echo 'pass-quiz';
                                                      } else {
                                                        echo 'fail-quiz';
                                                      }; ?> quiz-score mt-1"><?php echo round($scorePercent); ?>%</p>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-10 card-body mt-3 mb-4 text-center mx-5">
                                            <h4 class="mb-4">Try again?</h4>
                                            <div class="mx-auto">
                                                <button class="btn btn-primary mb-4 mx-2" onclick="location.replace('<?php echo SITE_URL; ?>quizzes-quiz-info')">Yes</button>
                                                <button class="btn btn-secondary mb-4 mx-2" onclick="location.replace('<?php echo SITE_URL . 'quizzes-logout'; ?>')">No</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>`);
          });
        }
      })();

      //
      function pulseText() {
        back.classed("pulse", true);
        label.classed("pulse", true);
        $(".pulse").addClass("animate__animated animate__pulse animate__infinite animate__faster");

        if ((timeLimit - timePassed) >= 0) {
          label.style("font-size", "24px")
            .attr("transform", "translate(0," + +4 + ")")
            .text(function(d) {
              return d.size - d.value;
            });
        }
        label.transition()
          .ease("elastic")
          .duration(900)
          .style("font-size", "24px")
          .attr("transform", "translate(0," + -1 + ")");
      }

      function destroyTimer() {
        label.transition()
          .ease("back")
          .duration(700)
          .style("opacity", "0")
          .style("font-size", "5")
          .attr("transform", "translate(0," + -40 + ")")
          .each("end", function() {
            field.selectAll("text").remove()
          });
        path.transition()
          .ease("back")
          .duration(700)
          .attr("d", nilArc);
        back.transition()
          .ease("back")
          .duration(700)
          .attr("d", nilArc)
          .each("end", function() {
            field.selectAll("path").remove()
          });
      }

      function arcTween(b) {
        var i = d3.interpolate({
          value: b.previous
        }, b);
        return function(t) {
          return arc(i(t));
        };
      }
    });
  </script>
</body>

</html>
<?php
include 'connection.php';

// Retrieve data from the "chambers" table
$sql = "SELECT * FROM chambers WHERE `status` = 1";
$result = $conn->query($sql);
$chambers = [];
// Check if there are any records
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $chambers[] = $row;
  }
}
function get_meta_value($page_id, $meta_key){
  global $conn;

  $sql = "SELECT `meta_value` FROM `page_meta` WHERE `page_id` = $page_id AND `meta_key` = '$meta_key'";
  $result = $conn->query($sql);
  // Check if there are any records
  if ($result->num_rows > 0) {
      $getMeta = $result->fetch_assoc();
      if(!empty($getMeta)){
        return $getMeta['meta_value'];
      }
  }
  
  return "";
}

include_once('./header.php');
?>
<!-- START SECTION BANNER -->
<section id="home_section" class="banner_section banner_shape bg_black3">
  <div class="banner_slide_content pb-0">
    <div class="container">
      <!-- STRART CONTAINER -->
      <?php
      $homePageDetails = [];
      $page_id = 1;
      
      $sql = "SELECT p.*, u.stored_filename FROM pages p LEFT JOIN uploads u ON p.featured_image_id = u.id WHERE p.id = $page_id";
      $result = $conn->query($sql);
      // Check if there are any records
      if ($result->num_rows > 0) {
          $homePageDetails = $result->fetch_assoc();
      } 
      
      $homeFeaturedImage = (!empty($homePageDetails) && $homePageDetails['stored_filename'] !="" ) ? "assets/uploads/".$homePageDetails['stored_filename'] : "assets/images/hero.png";
      
      $homeContent = (!empty($homePageDetails) && $homePageDetails['content'] !="" ) ? $homePageDetails['content'] : "";

      $specialist = get_meta_value($page_id, "specialist");
      $degree_1 = get_meta_value($page_id, "degree_1");
      $degree_2 = get_meta_value($page_id, "degree_2");
      $degree_3 = get_meta_value($page_id, "degree_3");
      
      ?>
      <div class="row justify-content-between align-items-center">
        <div class="col-xl-6 col-md-7 order-2 order-md-1 py-2">
          <div class="banner_content text_white banner_center_content">
            
            <?php echo ($homeContent !="") ? "<h2 class='animation' data-animation='fadeInUp' data-animation-delay='0.02s'>$homeContent</h2>" : "" ?>

            <?php echo ($specialist !="") ? "<div id='typed-strings' class='d-none'><b>$specialist</b></div>" : "" ?>
            
            <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.03s">
              <span id="typed-text" class="text_default"></span>
            </h4>
            
            <?php echo ($degree_1 !="") ? "<h4 class='animation' data-animation='fadeInUp' data-animation-delay='0.04s'>$degree_1</h4>" : "" ?>
            <?php echo ($degree_2 !="") ? "<h4 class='animation' data-animation='fadeInUp' data-animation-delay='0.05s'>$degree_2</h4>" : "" ?>
            <?php echo ($degree_3 !="") ? "<h4 class='animation' data-animation='fadeInUp' data-animation-delay='0.05s'>$degree_3</h4>" : "" ?>
            <a href="#appointment" class="page-scroll btn btn-default rounded-0 btn-aylen animation"
              data-animation="fadeInUp" data-animation-delay="0.06s">Book an Appointment</a>
          </div>
        </div>
        <div class="col-xl-5 col-md-5 order-1 order-md-2">
          <div class="banner_img animation" data-animation="fadeInUp" data-animation-delay="0.02s">
            <img src="<?php echo $homeFeaturedImage; ?>" alt="my_image" />
          </div>
        </div>
      </div>
    </div>
    <!-- END CONTAINER-->
  </div>
  <div class="social_banner social_vertical">
    <ul class="list_none social_icons text-center">
      <li>
        <a href="#" class="sc_facebook"><i class="ion-social-facebook"></i></a>
      </li>
      <li>
        <a href="#" class="sc_twitter"><i class="ion-social-twitter"></i></a>
      </li>
      <li>
        <a href="#" class="sc_google"><i class="ion-social-googleplus"></i></a>
      </li>
      <li>
        <a href="#" class="sc_youtube"><i class="ion-social-youtube-outline"></i></a>
      </li>
      <li>
        <a href="#" class="sc_instagram"><i class="ion-social-instagram-outline"></i></a>
      </li>
    </ul>
  </div>
</section>
<!-- END SECTION BANNER -->

<!-- START SECTION ABOUT US -->
<section id="about" class="bg_black4">
  <div class="container">
    <div class="row">
      <?php
      $aboutPageDetails = [];
      $page_id = 3;
      
      $sql = "SELECT p.*, u.stored_filename FROM pages p LEFT JOIN uploads u ON p.featured_image_id = u.id WHERE p.id = $page_id";
      $result = $conn->query($sql);
      // Check if there are any records
      if ($result->num_rows > 0) {
          $aboutPageDetails = $result->fetch_assoc();
      } 
      
      $aboutFeaturedImage = (!empty($aboutPageDetails) && $aboutPageDetails['stored_filename'] !="" ) ? "assets/uploads/".$aboutPageDetails['stored_filename'] : "assets/images/about_img.jpg";
      
      $aboutContent = (!empty($aboutPageDetails) && $aboutPageDetails['content'] !="" ) ? $aboutPageDetails['content'] : "";

      $dob = get_meta_value($page_id, "dob");
      $phone = get_meta_value($page_id, "phone");
      $email = get_meta_value($page_id, "email");
      $address = get_meta_value($page_id, "address");
      $website = get_meta_value($page_id, "website");
      
      ?>
      <div class="col-md-4">
        <div class="about_img animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <img src="<?php echo $aboutFeaturedImage; ?>" alt="about_img" />
        </div>
      </div>
      <div class="col-md-8">
        <div class="about_info text_white animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <div class="heading_s1 heading_light mb-3">
            <h2>About Me</h2>
          </div>
          <?php echo $aboutContent; ?>
          <hr />
          <div class="heading_s1 heading_light mb-4">
            <h5>Basic Info</h5>
          </div>
          <ul class="profile_info list_none">
            <?php echo ($dob !="") ? "<li><span class='title'>Date of Birth:</span><p>$dob</p></li>" : "" ?>
            <?php echo ($phone !="") ? "<li><span class='title'>Phone No:</span><p>$phone</p></li>" : "" ?>
            <?php echo ($email !="") ? "<li><span class='title'>Email:</span><p>$email</p></li>" : "" ?>
            <?php echo ($address !="") ? "<li><span class='title'>Address:</span><p>$address</p></li>" : "" ?>
            <?php echo ($website !="") ? "<li><span class='title'>Website:</span><p>$website</p></li>" : "" ?>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END SECTION ABOUT US -->

<!-- START SECTION Appointment -->
<section id="appointment" class="bg_black2">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-12 col-lg-12 col-md-12 text-center">
        <div class="heading_s1 heading_light animation text-center" data-animation="fadeInUp"
          data-animation-delay="0.02s">
          <h2>Book your appointment</h2>
        </div>
        <p class="animation text-white" data-animation="fadeInUp" data-animation-delay="0.03s">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus
          blandit massa enim. Nullam id varius nunc id varius nunc.
        </p>
      </div>
    </div>
    <div class="row animation" data-animation="fadeInUp" data-animation-delay="0.04s">
      <div class="col-12 text-center">
        <div class="appointment_form">
          <div class="row justify-content-center">
            <div class="col-md-6">
              <div class="field_form form_style3 animation" data-animation="fadeInUp" data-animation-delay="0.02s">
                <form method="post" name="appointment_form" id="appointment_form">
                  <div class="row">
                    <div class="form-group col-12">
                      <input required="required" placeholder="Patient Name *"
                        class="form-control text-light bg-dark p-2" name="patient_name" type="text" />
                    </div>
                    <div class="form-group col-12">
                      <input required="required" placeholder="Patient Mobile *"
                        class="form-control text-light bg-dark p-2" name="patient_mobile" type="text" />
                    </div>
                    <div class="form-group col-12">
                      <select required="required" class="form-control text-light bg-dark p-2" name="chamber_id"
                        id="chamber_id">
                        <option value="">Select Chamber</option>
                        <?php
                        // Populate the dropdown with chambers
                        foreach ($chambers as $chamber) {
                          echo "<option value='{$chamber['id']}'>{$chamber['name']}</option>";
                        }
                        ?>
                      </select>
                    </div>
                    <div class="form-group col-12">
                      <select required="required" class="form-control text-light bg-dark p-2" name="appointment_id"
                        id="appointment_date">
                        <option value="">Select Date</option>
                        <!-- Dates will be loaded dynamically using AJAX based on the selected chamber -->
                      </select>
                    </div>
                    <div class="col-lg-12">
                      <button type="button" title="Book Now" class="btn btn-default rounded-0 btn-aylen"
                        id="bookNowBtn">
                        Book Now
                      </button>
                    </div>
                    <div class="col-lg-12 text-center">
                      <div id="appointment-msg" class="alert-msg text-center"></div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>

        <!-- END of Calendar -->

      </div>
    </div>
  </div>
</section>
<!-- END SECTION SERVICES -->
<!-- START SECTION PORTFOLIO -->
<section id="portfolio" class="pb_70 bg_black4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-7 col-md-9 text-center">
        <div class="heading_s1 heading_light animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <h2>My Portfolio</h2>
        </div>
        <p class="animation text-white" data-animation="fadeInUp" data-animation-delay="0.03s">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus
          blandit massa enim. Nullam id varius nunc id varius nunc.
        </p>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="cleafix small_divider"></div>
      </div>
    </div>
    <div class="row mb-4 mb-md-5">
      <div class="col-md-12 text-center">
        <ul class="list_none grid_filter filter_tab2 filter_white animation" data-animation="fadeInUp"
          data-animation-delay="0.04s">
          <!-- Add your code to fetch file types from the galleries table -->
          <?php

          $limit = 6; // Number of images to display
          $sql = "SELECT DISTINCT file_type FROM galleries ORDER BY id DESC LIMIT $limit";
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            $first = true; // To add 'current' class to the first filter
            while ($row = $result->fetch_assoc()) {
              $fileType = $row['file_type'];
              $filterClass = strtolower(str_replace(" ", "-", $fileType));
              $currentClass = $first ? 'class="current"' : '';
              echo '<li><a href="#" ' . $currentClass . ' data-filter=".' . $filterClass . '">' . $fileType . '</a></li>';
              $first = false;
            }
          }
          ?>
        </ul>
      </div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <ul class="grid_container gutter_medium work_col3 portfolio_gallery portfolio_style2 animation"
          data-animation="fadeInUp" data-animation-delay="0.04s">
          <li class="grid-sizer"></li>
          <?php
          // Fetch the last 6 images from galleries
          $sqlImages = "SELECT g.id, g.title, g.file_type, u.stored_filename 
                                  FROM galleries g
                                  INNER JOIN uploads u ON g.upload_id = u.id
                                  ORDER BY g.id DESC LIMIT $limit";
          $resultImages = $conn->query($sqlImages);

          if ($resultImages->num_rows > 0) {
            while ($rowImage = $resultImages->fetch_assoc()) {
              $fileType = strtolower(str_replace(" ", "-", $rowImage['file_type']));
              echo '<li class="grid_item ' . $fileType . '">
                        <div class="portfolio_item" data-tilt>
                            <a href="#" class="image_link">
                                <img src="assets/uploads/' . $rowImage['stored_filename'] . '" alt="' . $rowImage['title'] . '" />
                            </a>
                            <div class="portfolio_content">
                                <div class="link_container">
                                    <a href="assets/uploads/' . $rowImage['stored_filename'] . '" class="image_popup"><i class="ion-image"></i></a>
                                </div>
                                <h5>
                                    ' . $rowImage['title'] . '
                                </h5>
                            </div>
                        </div>
                    </li>';
            }
          }
          ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<!-- END SECTION PORTFOLIO -->

<!-- START SECTION COUNTER -->
<section class="counter_wrap bg_black2">
  <div class="container">
    <div class="row">
      <div class="col-lg-3 col-md-3 col-6">
        <div class="box_counter text-center animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <i class="flaticon-briefing"></i>
          <h3 class="counter_text"><span class="counter">800</span>+</h3>
          <p>Projects Completed</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-6">
        <div class="box_counter text-center animation" data-animation="fadeInUp" data-animation-delay="0.03s">
          <i class="flaticon-laugh"></i>
          <h3 class="counter_text"><span class="counter">524</span></h3>
          <p>Happy Clients</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-6">
        <div class="box_counter text-center animation" data-animation="fadeInUp" data-animation-delay="0.04s">
          <i class="flaticon-coffee-cup"></i>
          <h3 class="counter_text"><span class="counter">654</span></h3>
          <p>Cup Of Tea</p>
        </div>
      </div>
      <div class="col-lg-3 col-md-3 col-6">
        <div class="box_counter text-center animation" data-animation="fadeInUp" data-animation-delay="0.05s">
          <i class="flaticon-trophy"></i>
          <h3 class="counter_text"><span class="counter">225</span></h3>
          <p>Awards Won</p>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END SECTION COUNTER -->

<!-- START SECTION CONTACT -->
<section id="contact" class="bg_black2">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <div class="heading_s1 heading_light animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <h2>Contact Me</h2>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-md-6">
        <div class="field_form form_style3 animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <form method="post" name="enq">
            <div class="row">
              <div class="form-group col-12">
                <input required="required" placeholder="Enter Name *" id="first-name" class="form-control" name="name"
                  type="text" />
              </div>
              <div class="form-group col-12">
                <input placeholder="Enter Email" id="email" class="form-control" name="email" type="email" />
              </div>
              <div class="form-group col-12">
                <input required="required" placeholder="Enter Mobile *" id="phone" class="form-control" name="phone"
                  type="text" />
              </div>
              <div class="form-group col-lg-12">
                <textarea placeholder="Message" id="description" class="form-control" name="message"
                  rows="5"></textarea>
              </div>
              <div class="col-lg-12">
                <button type="submit" title="Submit Your Message!" class="btn btn-default rounded-0 btn-aylen"
                  id="submitButton" name="submit" value="Submit">
                  Submit
                </button>
              </div>
              <div class="col-lg-12 text-center">
                <div id="alert-msg" class="alert-msg text-center"></div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="contact_map mt-4 mt-md-0 animation" data-animation="fadeInUp" data-animation-delay="0.03s">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15467.770341155656!2d88.18390104074729!3d22.19239329013198!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3a025fa2c05c1383%3A0x28f34e2d58d32495!2sDiamond%20Harbour%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1706039189451!5m2!1sen!2sin" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- START SECTION CONTACT -->

<!-- Add this in the head section of your HTML file -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
<!-- Add this script in your HTML file -->
<script>
  $(document).ready(function () {
    // Validate the appointment form using jQuery Validate
    $("#appointment_form").validate({
      rules: {
        patient_name: "required",
        patient_mobile: {
          required: true,
          digits: true,
          minlength: 10,
          maxlength: 10
        },
        chamber_id: "required",
        appointment_date: "required"
      },
      messages: {
        patient_mobile: {
          digits: "Please enter a valid mobile number",
          minlength: "Mobile number must be 10 digits",
          maxlength: "Mobile number must be 10 digits"
        }
      },
      submitHandler: function (form) {
        // Form is valid, submit it using AJAX
        $.ajax({
          type: "POST",
          url: "submit_appointment.php", // Change this to your actual PHP file
          data: $(form).serialize(), // Serialize the form data
          success: function (response) {
            // Handle the response from the server
            $("#appointment-msg").html(response);

            // Reset the form after successful submission
            form.reset();

            // Hide the success message after 5 seconds
            setTimeout(function () {
              $("#appointment-msg").empty();
            }, 5000);
          }
        });
      }
    });

    // Submit form when Book Now button is clicked
    $("#bookNowBtn").click(function () {
      $("#appointment_form").submit();
    });
  });
</script>

<script>
  $(document).ready(function () {
    // Function to load available dates based on selected chamber
    function loadDates() {
      var chamberId = $("#chamber_id").val();

      // Check if a chamber is selected
      if (chamberId) {
        // Use AJAX to fetch available dates for the selected chamber
        $.ajax({
          type: "GET",
          url: "get_available_dates.php",
          data: { chamber_id: chamberId },
          dataType: "json",
          success: function (dates) {
            var dateDropdown = $("#appointment_date");

            // Clear existing options
            dateDropdown.empty().append("<option value=''>Select Date</option>");

            // Populate the dropdown with available dates
            $.each(dates, function (index, date) {
              dateDropdown.append($("<option></option>")
                .attr("value", date.id)
                .text(date.appointment_date));
            });
          }
        });
      }
    }

    // Attach event listener to the chamber dropdown
    $("#chamber_id").change(loadDates);
  });
</script>
<?php
include_once('./footer.php');
?>
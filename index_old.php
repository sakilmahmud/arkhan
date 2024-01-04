<?php
include 'connection.php';
include_once('./header.php');
?>
<!-- START SECTION BANNER -->
<section id="home_section" class="banner_section banner_shape bg_black3">
  <div class="banner_slide_content pb-0">
    <div class="container">
      <!-- STRART CONTAINER -->
      <div class="row justify-content-between align-items-center">
        <div class="col-xl-6 col-md-7">
          <div class="banner_content text_white banner_center_content">
            <h2 class="animation" data-animation="fadeInUp" data-animation-delay="0.02s">
              Hello, I'm <br />Dr. A R Khan
            </h2>
            <div id="typed-strings" class="d-none">
              <b>MBBS - Calcutta Medical College</b>
              <b>MS - R G Kar Medical College</b>
            </div>
            <h4 class="animation" data-animation="fadeInUp" data-animation-delay="0.03s">
              <span id="typed-text" class="text_default"></span>
            </h4>
            <p class="animation" data-animation="fadeInUp" data-animation-delay="0.04s">
              Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed
              do eiusmod tempor incididunt ut labore.
            </p>
            <a href="#appointment" class="page-scroll btn btn-default rounded-0 btn-aylen animation"
              data-animation="fadeInUp" data-animation-delay="0.05s">Book an Appointment</a>
          </div>
        </div>
        <div class="col-xl-5 col-md-5">
          <div class="banner_img animation" data-animation="fadeInUp" data-animation-delay="0.02s">
            <img src="assets/images/my_image.png" alt="my_image" />
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
      <div class="col-md-4">
        <div class="about_img animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <img src="assets/images/about_img.png" alt="about_img" />
        </div>
      </div>
      <div class="col-md-8">
        <div class="about_info text_white animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <div class="heading_s1 heading_light mb-3">
            <h2>About Me</h2>
          </div>
          <p>
            Nam eget neque pellentesque efficitur neque at, ornare orci.
            Vestibulum ligula orci volutpat id aliquet eget, consectetur
            eget ante. Duis pharetra for nec rhoncus felis sagittis nec amet
            ultricies lorem.
          </p>
          <p>
            All the Lorem Ipsum generators on the Internet tend to repeat
            predefined chunks as necessary.Iipsum dolor sit amet consectetur
            adipiscing elitllus blandit massa enim.
          </p>
          <hr />
          <div class="heading_s1 heading_light mb-4">
            <h5>Basic Info</h5>
          </div>
          <ul class="profile_info list_none">
            <li>
              <span class="title">Date of birth:</span>
              <p>20 August 1990</p>
            </li>
            <li>
              <span class="title">Phone No:</span>
              <p>+ (123) 1512-578</p>
            </li>
            <li>
              <span class="title">Email:</span>
              <a href="mailto:info@sitename.com">mymail@gmail.com</a>
            </li>
            <li>
              <span class="title">Address:</span>
              <p>123 Street, Old Trafford, London</p>
            </li>
            <li>
              <span class="title">Website:</span>
              <p>www.mywebsite.com</p>
            </li>
            <li>
              <span class="title">Freelance:</span>
              <p>Available</p>
            </li>
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
        <!-- Start Calendar -->
        <?php
        // Get current month and next month
        $currentMonth = date('m');
        $currentYear = date('Y');
        $nextMonth = date('m', strtotime('+1 month'));
        $nextYear = date('Y', strtotime('+1 month'));

        // Get the first day and last day of the current month
        $firstDayOfMonth = date('Y-m-01');
        $lastDayOfMonth = date('Y-m-t');

        // Get the current date
        $today = date('Y-m-d');

        // Get events data from the bookings table
        $query = "SELECT DATE(appointment_date) AS ap_date, COUNT(*) AS appointment_count FROM bookings WHERE appointment_date >= '$today' GROUP BY DATE(appointment_date) ORDER BY ap_date";
        $result = mysqli_query($conn, $query);

        // Check if the query was successful
        if ($result) {
          $events = [];

          // Fetch data from the result set
          while ($row = mysqli_fetch_assoc($result)) {
            $appointmentCount = $row['appointment_count'];
            $eventTitle = "$appointmentCount Appointment" . ($appointmentCount > 1 ? 's' : '') . " Booked";
            $eventStart = $row['ap_date'];

            // Add the event to the events array
            $events[] = ["title" => $eventTitle, "start" => $eventStart];
          }
        } else {
          // Handle the error if the query fails
          echo 'Error: ' . mysqli_error($conn);
        }

        ?>
        <style>
          #calendar {
            max-width: 800px;
            margin: 0 auto;
          }

          /* Style for past dates */
          .fc-past {
            color: #999;
            /* Example styling for past dates */
            pointer-events: none;
            /* Disable pointer events for past dates */
            background-color: rgba(255, 255, 255, 0.3);
            /* Example background color for past dates */
          }

          .doctor-available {
            background-color: #aaffaa;
            /* Use your preferred color */
          }

          .fc-available {
            background-color: springgreen;
          }

          .fc-day-today {
            background-color: yellowgreen;
          }
        </style>
        <div class="calendar">
          <div id='calendar'></div>
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

<!-- START WORK EXPERIENCES -->
<section id="experience" class="bg_black4">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-7 col-md-9 text-center">
        <div class="heading_s1 heading_light animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <h2>Work Experiences</h2>
        </div>
        <p class="animation text-white" data-animation="fadeInUp" data-animation-delay="0.03s">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus
          blandit massa enim. Nullam id varius nunc id varius nunc.
        </p>
      </div>
    </div>
    <div class="row animation" data-animation="fadeInUp" data-animation-delay="0.04s">
      <div class="col-lg-4 col-sm-6">
        <div class="icon_box icon_box_style_2 box_dark">
          <div class="icon_box_content text_white">
            <h4>UI/UX Designer</h4>
            <p><span class="text_default">2002-2006</span> Adobe Inc.</p>
            <hr />
            <p>
              There are many variations of passages of Lorem Ipsum
              available, but the majority have suffered alteration in some
              form
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-6">
        <div class="icon_box icon_box_style_2 box_dark">
          <div class="icon_box_content text_white">
            <h4>Web Design</h4>
            <p><span class="text_default">2007-2010</span> Google Inc.</p>
            <hr />
            <p>
              There are many variations of passages of Lorem Ipsum
              available, but the majority have suffered alteration in some
              form
            </p>
          </div>
        </div>
      </div>
      <div class="col-lg-4 col-sm-6">
        <div class="icon_box icon_box_style_2 box_dark">
          <div class="icon_box_content text_white">
            <h4>Web Development</h4>
            <p><span class="text_default">2010-2013</span> Adobe Inc.</p>
            <hr />
            <p>
              There are many variations of passages of Lorem Ipsum
              available, but the majority have suffered alteration in some
              form
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END WORK EXPERIENCES -->

<!-- START SECTION TESTIMONIAL -->
<section class="bg_black2">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-6 col-lg-7 col-md-9 text-center">
        <div class="heading_s1 heading_light animation" data-animation="fadeInUp" data-animation-delay="0.02s">
          <h2>Clients Testimonials</h2>
        </div>
        <p class="animation text-white" data-animation="fadeInUp" data-animation-delay="0.03s">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus
          blandit massa enim. Nullam id varius nunc id varius nunc.
        </p>
        <div class="cleafix small_divider"></div>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-12 animation" data-animation="fadeInUp" data-animation-delay="0.04s">
        <div class="carousel_slider testimonial_style1 owl-carousel owl-theme" data-margin="20" data-dots="false"
          data-loop="true" data-autoplay="true"
          data-responsive='{"0":{"items": "1"}, "768":{"items": "2"}, "1199":{"items": "3"}}'>
          <div class="item">
            <div class="testimonial_box box_dark text_white">
              <div class="testimonial_user">
                <div class="testimonial_img">
                  <img src="assets/images/client_img1.jpg" alt="client" />
                </div>
                <div class="client_info">
                  <h6>Lissa Castro</h6>
                  <span>Developer</span>
                </div>
              </div>
              <div class="testi_meta">
                <p>
                  Sed ut perspiciatis unde omnis iste natus error sit
                  voluptatem accusantium doloremque laudantium, quaeillo
                  inventore veritatis et quasi architecto explicabo.
                </p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimonial_box box_dark text_white">
              <div class="testimonial_user">
                <div class="testimonial_img">
                  <img src="assets/images/client_img2.jpg" alt="client" />
                </div>
                <div class="client_info">
                  <h6>Alden Smith</h6>
                  <span>Creative Designer</span>
                </div>
              </div>
              <div class="testi_meta">
                <p>
                  Sed ut perspiciatis unde omnis iste natus error sit
                  voluptatem accusantium doloremque laudantium, quaeillo
                  inventore veritatis et quasi architecto explicabo.
                </p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimonial_box box_dark text_white">
              <div class="testimonial_user">
                <div class="testimonial_img">
                  <img src="assets/images/client_img3.jpg" alt="client" />
                </div>
                <div class="client_info">
                  <h6>Daisy Lana</h6>
                  <span>Creative Director</span>
                </div>
              </div>
              <div class="testi_meta">
                <p>
                  Sed ut perspiciatis unde omnis iste natus error sit
                  voluptatem accusantium doloremque laudantium, quaeillo
                  inventore veritatis et quasi architecto explicabo.
                </p>
              </div>
            </div>
          </div>
          <div class="item">
            <div class="testimonial_box box_dark text_white">
              <div class="testimonial_user">
                <div class="testimonial_img">
                  <img src="assets/images/client_img4.jpg" alt="client" />
                </div>
                <div class="client_info">
                  <h6>Helena Amos</h6>
                  <span>Creative Designer</span>
                </div>
              </div>
              <div class="testi_meta">
                <p>
                  Sed ut perspiciatis unde omnis iste natus error sit
                  voluptatem accusantium doloremque laudantium, quaeillo
                  inventore veritatis et quasi architecto explicabo.
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- END SECTION TESTIMONIAL -->

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
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235850.81212011125!2d88.18254112599966!3d22.535343439863773!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39f882db4908f667%3A0x43e330e68f6c2cbc!2sKolkata%2C%20West%20Bengal!5e0!3m2!1sen!2sin!4v1702958551263!5m2!1sen!2sin"
            allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- START SECTION CONTACT -->

<!-- Booking Details Modal -->
<div class="modal" id="booking_details_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Booked Appointments</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <!-- AJAX response content will be displayed here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal" id="booking_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Book Now</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="booking_appointment.php" method="POST" id="booking_form">
          <input type="text" class="form-control mb-2" name="your_name" placeholder="Enter Your Name">
          <input type="text" class="form-control" name="your_phone" placeholder="Enter Your Mobile Number">
          <input type="hidden" name="appointment_date" id="appointment_date">
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary submit_form">Save changes</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Booking Modal -->
<!-- Thank You Modal -->
<div class="modal" id="thanks_modal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thank You</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Your Booking is successfully completed!<br>
        You will get confirmation soon.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<!-- End of Thank You Modal -->

<script>
  document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      events: <?php echo json_encode($events); ?>,
      headerToolbar: {
        left: 'prev,next today',
        center: 'title',
        right: 'dayGridMonth,timeGridWeek,timeGridDay'
      },
      selectable: true,
      eventClick: function (info) {
        console.log('Event: ', info.event.startStr);
        console.log('Event: ', info.event.title);
        show_booked_appointments(info.event.startStr);
      },
      dayMaxEvents: true,
      dateClick: function (info) {
        //alert('Clicked on: ' + info.dateStr);
        custom(info);
      },
      select: function (info) {
        //alert('Selected from: ' + info.startStr + ' to ' + info.endStr);
        custom2(info);
      },
      validRange: {
        start: '<?php echo date("Y-m-d", strtotime($firstDayOfMonth)); ?>',
        end: '<?php echo date("Y-m-t", strtotime($nextYear . "-" . $nextMonth . "-01")); ?>'
      },
      dayCellDidMount: function (arg) {
        // Check if the date is in the past
        if (arg.date < new Date()) {
          // Add the 'fc-past' class to the past date cells
          arg.el.classList.add('fc-past');
        }
        // Check if the date is Sunday, Thursday, or Saturday
        var dayOfWeek = arg.date.getDay();
        if ([0, 3, 6].includes(dayOfWeek)) {
          // Add the 'fc-disabled' class to disable other days
          arg.el.classList.add('fc-available');
        }
      },
    });

    calendar.render();

    function show_booked_appointments(booking_date) {
      // Perform AJAX request
      $.ajax({
        type: 'POST',
        url: 'get_appointments.php',
        data: { booking_date: booking_date },
        success: function (response) {
          // Update the modal title with the booking date
          $('#booking_details_modal .modal-title').text('Booked Appointments on ' + booking_date);

          // Display the booked appointments data in the modal body
          $('#booking_details_modal .modal-body').html(response);

          // Show the booking details modal
          $('#booking_details_modal').modal('show');
        },
        error: function (error) {
          console.error('AJAX Error: ', error);
        }
      });
    }

    function custom(info) {
      //console.log("1st function", info);
      $('#booking_modal').modal('show');
      console.log(info.dateStr);
      $('#appointment_date').val(info.dateStr);
    }
    function custom2(info) {
      console.log("2nd function", info);
    }

  });

</script>
<script>
  $(document).ready(function () {
    // Handle form submission
    $('.submit_form').on('click', function () {
      // Get form data
      var formData = $('#booking_form').serialize();

      // Perform AJAX request
      $.ajax({
        type: 'POST',
        url: 'booking_appointment.php',
        data: formData,
        success: function (response) {
          // Handle the response from the server
          console.log(response);

          // Check if the response indicates success
          if (response.trim() === 'Booking successful!') {
            // Close the booking modal
            $('#booking_modal').modal('hide');

            // Show the thank you modal
            $('#thanks_modal').modal('show');
          } else {
            // Handle other cases if needed
            console.error('Booking failed: ', response);
          }
        },
        error: function (error) {
          console.error('AJAX Error: ', error);
        }
      });
    });
  });
</script>
<?php
include_once('./footer.php');
?>
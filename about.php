

<?php 

$css_file = "styles/styles-about.css";
include("header.inc"); 
include("nav.inc"); 
?>

  <main>
    <section>
      <h2>RENEW</h2>
      <h2>About Our Team</h2>
      <hr>
      <p>
        We are the team behind the G03 Sustainable Energy Solutions Company website. Our goal is to create
        a professional, accessible, and engaging website that reflects the company’s commitment to renewable
        energy, public awareness, and digital innovation.
      </p>
    </section>

    <section>
      <h2>Group Information</h2>
      <ul>
        <li><strong>Group Name:</strong> G03 – Sustainable Energy Solutions Company</li>
        <li><strong>Class Day:</strong> Wednesday</li>
        <li><strong>Class Time:</strong> 10:30 AM – 12:30 PM</li>
      </ul>
    </section>

    <section>
      <h2>Member Contributions</h2>

      <?php
require_once "settings.php";

$conn = mysqli_connect($host, $user, $pwd, $sql_db);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$query = "SELECT * FROM about";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo '<div class="card">';
    echo '<h3>' . htmlspecialchars($row["member_name"]) . '</h3>';
    echo '<p>' . htmlspecialchars($row["contribution"]) . '</p>';
    echo '</div>';
}

mysqli_close($conn);
?>
    </section>

    <section>
      <h2>Team Quotes</h2>

      <div class="quote-box">
        <h3>Moni</h3>
        <p>“សូមធ្វើការតូចៗឱ្យបានល្អ ដើម្បីបង្កើតអ្វីធំៗនៅថ្ងៃក្រោយ”</p>
        <p class="translation">English: “Do small things well today to build something great tomorrow.”</p>
      </div>

      <div class="quote-box">
        <h3>Maria</h3>
        <p>“ائیدار ترقی کا راستہ صاف توانائی سے ہو کر گزرتا ہے۔”</p>
        <p class="translation">English: “The path to sustainable development runs through clean energy.”</p>
      </div>

      <div class="quote-box">
        <h3>Karim</h3>
        <p>“L'énergie la plus propre est celle que nous ne consommons pas.”</p>
        <p class="translation">English: “The cleanest energy is the energy we don't consume.”</p>
      </div>

      <div class="quote-box">
        <h3>Ishmam</h3>
        <p>“পেরা নাই, চিল”</p>
        <p class="translation">English: “No stress, just chill.”</p>
      </div>
    </section>

    <section>
      <h2>Our Group Photo</h2>
      <figure>
        <img class="group-photo" src="images/group-photo.jpg" alt="Group photo of the web development team">
        <figcaption>Our team collaborating on the Sustainable Energy Solutions website project.</figcaption>
      </figure>
    </section>

    <section>
      <h2>Fun Facts About Our Team</h2>
      <table>
        <caption>Meet the team behind the project</caption>
        <thead>
          <tr>
            <th>Name</th>
            <th>Dream Job</th>
            <th>Coding Snack</th>
            <th>Hometown</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>Moni</td>
            <td>Cybersecurity Analyst</td>
            <td>Iced Coffee</td>
            <td>Melbourne</td>
          </tr>
          <tr>
            <td>Karim</td>
            <td>Network Security Engineer</td>
            <td>Avocado</td>
            <td>Melbourne</td>
          </tr>
          <tr>
            <td>Maria</td>
            <td>Software Engineer or Data Scientist</td>
            <td>Ben & Jerry's</td>
            <td>Melbourne</td>
          </tr>
          <tr>
            <td>Ishmam</td>
            <td>Sleeper</td>
            <td>Potato Chips</td>
            <td>Melbourne</td>
          </tr>
        </tbody>
      </table>
    </section>

    <section>
      <h2>Acknowledgement of Country</h2>
      <p>
        We acknowledge the Traditional Owners of the lands on which we work and learn, and we pay our respects
        to Elders past and present. We recognise their continuing connection to land, waters, and community,
        and we value the importance of sustainability and care for Country.
      </p>
      <p class="highlight-text">
        Sustainable Energy Solutions is committed to an inclusive and respectful future for all communities.
      </p>
    </section>
  </main>
  <?php

  
  $footer_extra = '
    <p>| Group G03</p>
    <p><a href="mailto:info@sustainableenergy.com">info@sustainableenergy.com</a></p>';
  

  include("footer.inc"); ?>





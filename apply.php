<?php
$css_file = "CSS/styles-apply.css";
include("header.inc");
include("nav.inc");
?>


     <style>
        header h1 {
        color: rgb(255, 255, 255);
        }
     </style>




<!-- Job Application-->
<main>      
    <h1>Application form</h1>

        <form action="process_eoi.php" method="post">

<!-- personal information -->
        <fieldset >
        <legend>Personal Details</legend>
            
            <div>
                <label for="jobReferance">Reference Number:</label>
                <input type="text" id="jobReferance" name="job_reference" required 
                        pattern="[A-Za-z0-9]{5}" title="5 alphanumeric characters">
            
            <div class="name">
                <div>
                        <label for="firstName">First Name:</label>
                        <input type="text" id="firstName" name="first_name" required 
                                maxlength="20" pattern="[A-Za-z ]+" title="Max 20 alpha characters">
                </div>
            
                <div>
                        <label for="lastName">Last Name:</label>
                        <input type="text" id="lastName" name="last_name" required 
                                maxlength="20" pattern="[A-Za-z ]+" title="Max 20 alpha characters">
                </div>
            </div>
            
            <label for="dateOfBirth">Date of Birth:</label>
            <input type="date" id="dateOfBirth" name="date_of_birth" required title="Format: dd/mm/yyyy">
                    
            </div>

<!-- gendercodes  -->
        <fieldset class="radio-group">
        <legend>Gender</legend>
            <div>
                <input type="radio" id="male" name="gender" value="male" required>
                <label for="male">Male</label>
            </div>
                
           <div>
                <input type="radio" id="female" name="gender" value="female">
                <label for="female">Female</label>
           </div>
                
            <div>
                <input type="radio" id="other" name="gender" value="other">
                <label for="other">Others</label>
            </div>
        </fieldset>

        </fieldset>

<!-- Address information -->
        <fieldset>
        <legend>Address</legend>
            <div id="address1">
                <label for="street">Street Address:</label>
                <input type="text" id="street" name="street" required maxlength="40">

                <label for="suburb">Suburb/Town:</label>
                <input type="text" id="suburb" name="suburb" required maxlength="40">
            </div>

            <div id="address2">
                <label for="state">State:</label>
                <select id = "state" name="state" required>
                        <option value="">Please Select</option>
                        <option value="VIC">VIC</option>
                        <option value="NSW">NSW</option>
                        <option value="QLD">QLD</option>
                        <option value="NT">NT</option>
                        <option value="WA">WA</option>
                        <option value="SA">SA</option>
                        <option value="TAS">TAS</option>
                        <option value="ACT">ACT</option>        
                </select>

            <label for="postcode">Postcode:</label>
            <input type="text" id="postcode" name="postcode" required 
                   pattern="\d{4}" title="Exactly 4 digits">
            </div>

        </fieldset>

<!-- contact information -->
        <fieldset class="contacts">
        <legend>Contact Info</legend>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div>
                <label for="number">Phone Number:</label>
                <input type="text" id="number" name="phone" required 
                       pattern="\d{8,12}" title="8 to 12 digits">
            </div>

        </fieldset>

<!-- required skills -->
<!-- used Gen Ai to brainstrom skills relate to Sustainable energy solutions -->
        <fieldset>
            <legend>Skills</legend>
                <label>Skill List:</label><br>
                <input type="checkbox" id="skill1" name="skills[]" value="Renewable Technology Expertise" required>
                <label for="skill1">Renewable Technology Expertise</label><br>
                <input type="checkbox" id="skill2" name="skills[]" value="Data Analytics & Digital Literacy">
                <label for="skill2">Data Analytics & Digital Literacy</label><br>
                <input type="checkbox" id="skill3" name="skills[]" value="Energy Auditing & Efficiency">
                <label for="skill3">Energy Auditing & Efficiency</label><br>
                <input type="checkbox" id="skill4" name="skills[]" value="Regulatory & Compliance Knowledge">
                <label for="skill4">Regulatory & Compliance Knowledge</label><br>
                <label for="otherSkills">Other Skills:</label><br>
                <textarea id="otherSkills" name="other_skills" rows="4" cols="40" 
                          placeholder="Tell us more about your expertise..."></textarea>

        </fieldset>
                <button id="submit" type="submit">Submit</button>
                <button id="reset" type="reset">Reset</button> 
        </form>
</main>

<?php include("footer.inc"); ?>
    

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <?php
        require_once 'header.php';
    ?>

    <div class="manage-container">
        <div class="box">
            <div class="card-header">
                <h2>Contact Us</h2>
            </div>
            
            <form id="contact-form">
                <div class="form-row">
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                    </div>
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email address">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea 
                        id="message" 
                        name="message" 
                        class="review-textarea" 
                        placeholder="Write your message here..."
                        rows="6"
                    ></textarea>
                </div>
                
                <button type="submit" class="success">Send Message</button>
            </form>
        </div>
    </div>

    <script src="js/contact.js"></script>

    <?php
        require_once 'footer.php';
    ?>
</body>
</html>
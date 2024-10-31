<?php

        use Infobip\Configuration;
        use Infobip\Api\SmsApi;
        use Infobip\Model\SmsDestination;
        use Infobip\Model\SmsTextualMessage;
        use Infobip\Model\SmsAdvancedTextualRequest;

        require __DIR__ . "/vendor/autoload.php";

        $apiURL = "n86yn5.api.infobip.com";
        $apiKey = "926fcf39cce7fb9101a66e620f0cef38-81f2ef95-1d32-4db8-9c3b-b63a14a8d481";
 

        $configuration = new Configuration(host: $apiURL, apiKey: $apiKey);
        $api = new SmsApi(config: $configuration);
   

        if (isset($_FILES['csv'])) {
            $message = $_POST["message"];
            $errors = [];

            // Get uploaded file info
            $file_name = $_FILES['csv']['name'];
            $file_tmp = $_FILES['csv']['tmp_name'];
            $file_type = $_FILES['csv']['type'];

            // Validate file type (optional, adjust based on your needs)
            $allowed_extensions = ['csv'];
            if (!in_array(pathinfo($file_name, PATHINFO_EXTENSION), $allowed_extensions)) {
                $errors[] = 'Only CSV files are allowed.';
            }

            // Check for any errors
            if (empty($errors)) {

                // Open the uploaded file for reading (without saving)
                $handle = fopen($file_tmp, "r");

                // Check if file opened successfully
                if ($handle) {

                    // Get the first line (header row)
                    $numbers = fgetcsv($handle);

                    // Print the phone numbers
                    foreach ($numbers as $number) {
                        // echo $number . "<br/>";

                        $destination = new SmsDestination(to: $number);

                        $theMessage = new SmsTextualMessage(
                            destinations: [$destination],
                            text: $message,
                            from: "meshack omwoyo"
                        );

                        $request = new SmsAdvancedTextualRequest(messages: [$theMessage]);

                        $response = $api->sendSmsMessage($request);

                        echo "message sent";
                    }

                    // Close the file handle
                    fclose($handle);
                } else {
                    $errors[] = 'Failed to open the uploaded file.';
                }
            } else {
                // Display any upload errors
                echo 'Upload failed: ';
                echo implode(', ', $errors);
            }
        }

        ?>
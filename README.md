CakePHP 2.0 Component Amazon AWS S3
============================

CakePHP 2.0 component using Amazon's AWS S3 API

Tools
-----------
- Upload object to S3 using both public and private ACL
- Download object to your apps TMP folder
- Secure Read from S3
- Delete object
- Check if object exists

Configuration
---------------

amazon/config.inc.php - Your Amazon AWS S3 KEY and SECRET are stored here
AmazonComponent.php - The name of the bucket is declared here

To work in CakePHP 1.3

Change 

class AmazonComponent extends Object

to 

class AmazonComponent extends Component

You will also need to change the name of the component from AmazonComponent.php to amazon.php

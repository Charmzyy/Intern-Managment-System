
     

     <!DOCTYPE html>
     <html>
     <head>
     
       <meta charset="utf-8">
       <meta http-equiv="x-ua-compatible" content="ie=edge">
       <title>Application Rejection</title>
       <meta name="viewport" content="width=device-width, initial-scale=1">
       <style type="text/css">
       /**
        * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
        */
       @media screen {
         @font-face {
           font-family: 'Source Sans Pro';
           font-style: normal;
           font-weight: 400;
           src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
         }
     
         @font-face {
           font-family: 'Source Sans Pro';
           font-style: normal;
           font-weight: 700;
           src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
         }
       }
     
       /**
        * Avoid browser level font resizing.
        * 1. Windows Mobile
        * 2. iOS / OSX
        */
       body,
       table,
       td,
       a {
         -ms-text-size-adjust: 100%; /* 1 */
         -webkit-text-size-adjust: 100%; /* 2 */
       }
     
       /**
        * Remove extra space added to tables and cells in Outlook.
        */
       table,
       td {
         mso-table-rspace: 0pt;
         mso-table-lspace: 0pt;
       }
     
       /**
        * Better fluid images in Internet Explorer.
        */
       img {
         -ms-interpolation-mode: bicubic;
       }
     
       /**
        * Remove blue links for iOS devices.
        */
       a[x-apple-data-detectors] {
         font-family: inherit !important;
         font-size: inherit !important;
         font-weight: inherit !important;
         line-height: inherit !important;
         color: inherit !important;
         text-decoration: none !important;
       }
     
       /**
        * Fix centering issues in Android 4.4.
        */
       div[style*="margin: 16px 0;"] {
         margin: 0 !important;
       }
     
       body {
         width: 100% !important;
         height: 100% !important;
         padding: 0 !important;
         margin: 0 !important;
       }
     
       /**
        * Collapse table borders to avoid space between cells.
        */
       table {
         border-collapse: collapse !important;
       }
     
       a {
         color: #1a82e2;
       }
     
       img {
         height: auto;
         line-height: 100%;
         text-decoration: none;
         border: 0;
         outline: none;
       }
       </style>
     
     </head>
     <body style="background-color: #e9ecef;">
     
       <!-- start preheader -->
       <div class="preheader" style="display: none; max-width: 0; max-height: 0; overflow: hidden; font-size: 1px; line-height: 1px; color: #fff; opacity: 0;">
       
         Thank you for applying for internship atintern .
       </div>
       <!-- end preheader -->
     
       <!-- start body -->
       <table border="0" cellpadding="0" cellspacing="0" width="100%">
     
         <!-- start logo -->
         <tr>
           <td align="center" bgcolor="#e9ecef">
             <!--[if (gte mso 9)|(IE)]>
             <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
             <tr>
             <td align="center" valign="top" width="600">
             <![endif]-->
             <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
               <tr>
                 <td align="center" valign="top" style="padding: 36px 24px;">
                  {{-- static logo --}}
                  
                     
                      
                 </td>
               </tr>
             </table>
             <!--[if (gte mso 9)|(IE)]>
             </td>
             </tr>
             </table>
             <![endif]-->
           </td>
         </tr>
         <!-- end logo -->
     
         <!-- start hero -->
         <tr>
           <td align="center" bgcolor="#e9ecef">
             <!--[if (gte mso 9)|(IE)]>
             <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
             <tr>
             <td align="center" valign="top" width="600">
             <![endif]-->
             <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
               <tr>
                 <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                   <h1 style="margin: 0; font-size: 20px; font-weight: 700; letter-spacing: -1px; line-height: 48px;">Unsuccessfull Application Review</h1>
                 </td>
               </tr>
             </table>
             <!--[if (gte mso 9)|(IE)]>
             </td>
             </tr>
             </table>
             <![endif]-->
           </td>
         </tr>
         <!-- end hero -->
     
         <!-- start copy block -->
         <tr>
           <td align="center" bgcolor="#e9ecef">
             <!--[if (gte mso 9)|(IE)]>
             <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
             <tr>
             <td align="center" valign="top" width="600">
             <![endif]-->
             <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
     
               <!-- start copy -->
               <tr>
                 <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                     <p>Dear {{$data['attachee']->fullname }}</p>
                     <p style="margin: 0;">Thank you for applying for the {{ $data['attachee']->role->role }} Internship in our organisation.
                       We appreciate your interest in our organization and the time you took to submit your application.</p>
                   <p>After careful consideration, we regret to inform you that you have not been selected for this position.
                     </p>
                   <p>We recognize the effort you put into your application and want to provide you with some feedback on areas where you can improve for future job applications. Unfortunately, we were unable to select you for the position due to this reason {{$data['attachee']->reason}}. We encourage you to work on developing more skills and gaining more experience
                     in this area so that you can become a stronger candidate for future job opportunities.</p>
                   
                     <p> We encourage you to keep an eye on our website and other job portals for future internship opportunities with us. We would be happy to consider your application again in the future.</p>
                 </td>
               </tr>
               <!-- end copy -->
     
       
     
         
     
               <!-- start copy -->
               <tr>
                 <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                   <p style="margin: 0;">Best Regards<br> Admin</p>
                 </td>
               </tr>
               <!-- end copy -->
     
             </table>
             <!--[if (gte mso 9)|(IE)]>
             </td>
             </tr>
             </table>
             <![endif]-->
           </td>
         </tr>
         <!-- end copy block -->
     
         <!-- start footer -->
         <tr>
           <td align="center" bgcolor="#e9ecef" style="padding: 24px;">
             <!--[if (gte mso 9)|(IE)]>
             <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
             <tr>
             <td align="center" valign="top" width="600">
             <![endif]-->
             <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
     
               <!-- start permission -->
               <tr>
                 <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                   <p style="margin: 0;">You received this email because you had  applied for internship atintern  . If you didn't request this you can safely delete this email.</p>
                 </td>
               </tr>
               <!-- end permission -->
     
               <!-- start unsubscribe -->
               <tr>
                 <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 14px; line-height: 20px; color: #666;">
                  
                   <p style="margin: 0;">For further assistance, please contact us at <a href="mailto:oaknetsup@gmail.com">Oaknet Support</a> or call us at 123-456-7890.</p>
                   <p style="margin: 0;">Oaknet Business Ralph Bunche road,  upperhill Nairobi</p>
                 </td>
               </tr>
             
               <!-- end unsubscribe -->
               <!-- start social media icons -->
     <tr>
       <td align="center" bgcolor="#e9ecef" style="padding: 12px 24px;">
         <table border="0" cellpadding="0" cellspacing="0" width="100%">
           <tr>
             <td align="center" style="padding: 0 10px;">
               <a href="https://www.facebook.com/" target="_blank" style="color: #ffffff;">
                 <img src="https://cdn3.iconfinder.com/data/icons/capsocial-round/500/facebook-512.png" alt="Facebook" width="30" height="30" style="display: block;" border="0" />
               </a>
             </td>
             <td align="center" style="padding: 0 10px;">
               <a href="https://twitter.com/" target="_blank" style="color: #ffffff;">
                 <img src="https://cdn3.iconfinder.com/data/icons/capsocial-round/500/twitter-512.png" alt="Twitter" width="30" height="30" style="display: block;" border="0" />
               </a>
             </td>
             <td align="center" style="padding: 0 10px;">
               <a href="https://www.instagram.com/" target="_blank" style="color: #ffffff;">
                 <img src="https://cdn3.iconfinder.com/data/icons/capsocial-round/500/instagram-512.png" alt="Instagram" width="30" height="30" style="display: block;" border="0" />
               </a>
             </td>
           </tr>
         </table>
       </td>
     </tr>
     <!-- end social media icons -->
     
     
             </table>
             <!--[if (gte mso 9)|(IE)]>
             </td>
             </tr>
             </table>
             <![endif]-->
           </td>
         </tr>
         <!-- end footer -->
     
       </table>
       <!-- end body -->
     
     </body>
     </html>


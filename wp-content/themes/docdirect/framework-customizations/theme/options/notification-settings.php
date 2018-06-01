<?php

if (!defined('FW')) {
    die('Forbidden');
}


$options = array(
    'notification_settings' => array(
        'type' => 'tab',
        'title' => esc_html__('Email Settings', 'docdirect'),
        'options' => array(
			'email_from_name' => array(
				'type'  => 'text',
				'value' => 'DocDirect',
				'label' => esc_html__('Email From Name', 'docdirect'),
				'desc'  => esc_html__('Add FROM NAME when email sent. Like: DocDirect', 'docdirect'),
			),
			'email_from_id' => array(
				'type'  => 'text',
				'value' => 'info@no-reply.com',
				'label' => esc_html__('FROM : Email ID', 'docdirect'),
				'desc'  => esc_html__('Add FROM EMAIL when email sent. Like: info@no-reply.com', 'docdirect'),
			),
			'email_logo' => array(
				'type'  => 'upload',
				'label' => esc_html__('Email Logo', 'docdirect'),
				'hint' => esc_html__('It will display only at the bottom of email templates.', 'docdirect'),
				'desc'  => esc_html__('Upload Your Logo Here the preferred size is 170 by 30.', 'docdirect'),
				'images_only' => true,
			),
			'notification_settings' => array(
				'type' => 'tab',
				'title' => esc_html__('General Templates', 'docdirect'),
				'options' => array(
					'register_user' => array(
						'title' => esc_html__('Email Content - Registration', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'register_subject' => array(
								'type' => 'text',
								'value' => 'Thank you for registering!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add Subject for email', 'docdirect'),
							),
							'info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%name% — To display the person\'s name. <br/>
								%email% — To display the person\'s email address.<br/>
								%username% — To display the username for login.<br/>
								%password% — To display the password for login.<br/>
								%logo% — To display site logo.<br/>',
							),
							'register_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hey %name%!<br/>

											Thanks for registering at DocDirect. You can now login to manage your account using the following credentials:
											<br/>
											Username: %username%<br/>
											Password: %password%<br/>

											Sincerely,<br/>
											DocDirect Team<br/>
											%logo%
											',
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
					'package_payment' => array(
						'title' => esc_html__('Payments (Invoice Detail)', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'invoice_subject' => array(
								'type' => 'text',
								'value' => 'Thank you for purchasing package!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add Subject for email', 'docdirect'),
							),
							'info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%name% — To display the person\'s name. <br/>
								%email% — To display the person\'s email address.<br/>
								%invoice% — To display the invoice id for payment.<br/>
								%package_name% — To display the package name.<br/>
								%amount% — To display the payment amount.<br/>
								%status% — To display the payment status.<br/>
								%method% — To display payment mehtod.<br/>
								%date% — To display purchase date.<br/>
								%expiry% — To display package expiry date.<br/>
								%address% — To display payer address.<br/>
								%logo% — To display site logo.<br/>',

							),
							'payment_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hey %name%!<br/>

											Thanks for purchasing the package. Your payment has been received and your invoice detail is given below:
											<br/>
											Invoice ID: %invoice%<br/>
											Package Name: %package_name%<br/>
											Payment Amount: %amount%<br/>
											Payment status: %status%<br/>
											Payment Method: %method%<br/>
											Purchase Date: %date%<br/>
											Expiry Date: %expiry%<br/>
											Address: %address%<br/>

											Sincerely,<br/>
											DocDirect Team<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,

								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
					'rating' => array(
						'title' => esc_html__('Rating ( Received )', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'rating_subject' => array(
								'type' => 'text',
								'value' => 'New rating received!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add Subject for email', 'docdirect'),
							),
							'info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%name% — To display the person\'s name. <br/>
								%rating_from% — To display the person name who rate user.<br/>
								%reason% — To display the rating subject.<br/>
								%link% — To display the rating page link.<br/>
								%rating% — To display the rating.<br/>
								%logo% — To display site logo.<br/>',

							),
							'rating_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hey %name%!<br/>

											A new rating has been received, Detail for rating is given below:
											<br/>
											Rating: %rating%<br/>
											Rating From: %rating_from%<br/>
											Reason: %reason%<br/>
											Comment: <br/>
											---------------------------------------<br/>
											You can view this at %link%

											Sincerely,<br/>
											DocDirect Team<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,

								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
					'account_verification_email' => array(
                        'title' => esc_html__('Account Verification', 'docdirect'),
                        'type' => 'tab',
                        'options' => array(
                            'ave_subject' => array(
                                'type' => 'text',
                                'value' => 'Account Verification',
                                'label' => esc_html__('Subject', 'docdirect'),
                                'desc' => esc_html__('Please add subject for account verification.', 'docdirect'),
                            ),
                            'ave_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'attr' => array(),
                                'label' => esc_html__('Email settings', 'docdirect'),
                                'desc' => esc_html__('', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'html' => '%name% — To display the person\'s name. <br/>
								%link% — Verify account link.<br/>
								%logo% — To display site logo.<br/>',
                            ),
                            'ave_content' => array(
                                'type' => 'wp-editor',
                                'value' => 'Hi %name%!<br/>

											<p><strong>Verify Your Account</strong></p>
											<p>You account has created with given below email address:</p>
											<p>Email Address: %account_email%</p>
											<p>If this was a mistake, just ignore this email and nothing will happen.</p>
											<p>To verifiy your account, click below link:</p>
											<p><a style="color: #fff; padding: 0 50px; margin: 0 0 15px; font-size: 20px; font-weight: 600; line-height: 60px; border-radius: 8px; background: #5dc560; vertical-align: top; display: inline-block; font-family: "Work Sans", Arial, Helvetica, sans-serif; text-decoration: none;" href="%link%">Verify</a></p><br />
											Sincerely,<br/>
											%logo%
											',
                                'attr' => array(),
                                'label' => esc_html__('Account Verification?', 'docdirect'),
                                'desc' => esc_html__('', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'size' => 'large', // small, large
                                'editor_height' => 400,
                            )
                        ),
                    ),
					'invitation' => array(
						'title' => esc_html__('Invitation', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'invitation_subject' => array(
								'type' => 'text',
								'value' => 'You have invitation for signup!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add Subject for email', 'docdirect'),
							),
							'invitation_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%username% — To display the username who send invitation. <br/>
								%link% — To display link for signup.<br/>
								%message% — To display user message.<br/>
								%logo% — To display site logo.<br/>',

							),
							'invitation_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hi,<br/>

											%username% has invited you to signup at %link%. You have invitation message given below
											<br/>
											%message%
											<br/>
											Sincerely,<br/>
											DocDirect Team<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'contact' => array(
						'title' => esc_html__('Contact Form', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'contact_email'     => array(
								'type'  => 'html',
								'value' => '',
								'label' => esc_html__('', 'docdirect'),
								'desc'  => esc_html__('This template will be use for professional users and site contact form to send an email from vistors.', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '',
							),
							'contact_subject' => array(
								'type' => 'text',
								'value' => 'Contact Form Received',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add Subject for email', 'docdirect'),
							),
							'contact_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%subject% — Contact subject <br/>
								%name% — To display username who contact the professional.<br/>
								%email% — To display email who contact the professional.<br/>
								%phone% — To display phone number who contact the professional.<br/>
								%message% — To display user message.<br/>
								%logo% — To display site logo.<br/>',

							),
							'contact_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hello,<br/>

											A person has contact you, description of message is given below.<br/><br/>
											Subject : %subject%<br/>
											Name : %name%<br/>
											Email : %email%<br/>
											Phone Number : %phone%<br/>
											Message : %message%<br/><br/><br/>

											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'lp_email' => array(
						'title' => esc_html__('Lost Password', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'lp_subject' => array(
								'type' => 'text',
								'value' => 'Forgot Password',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add subject for lost password.', 'docdirect'),
							),
							'lp_info' => array(
								'type' => 'html',
								'value' => '',
								'attr' => array(),
								'label' => esc_html__('Email settings', 'docdirect'),
								'desc' => esc_html__('', 'docdirect'),
								'help' => esc_html__('', 'docdirect'),
								'html' => '%username% — To display the person\'s name. <br/>
								%link% — To display the lost password link.<br/>
								%logo% — To display site logo.<br/>',
							),
							'lp_content' => array(
								'type' => 'wp-editor',
								'value' => 'Hey %name%!<br/>

											<p><strong>Lost Password reset</strong></p>
											<p>Someone requested that the password be reset for the following account:</p>
											<p>Email Address: %account_email%</p>
											<p>If this was a mistake, just ignore this email and nothing will happen.</p>
											<p>To reset your password, click reset link below:</p>
											<p><a href="%link%">Reset</a></p>
											Sincerely,<br/>
											DocDirect Team<br/>
											%logo%
											',
								'attr' => array(),
								'label' => esc_html__('Lost Password?', 'docdirect'),
								'desc' => esc_html__('', 'docdirect'),
								'help' => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						),
					),
					'qa_email' => array(
                        'title' => esc_html__('Post Questions', 'docdirect'),
                        'type' => 'tab',
                        'options' => array(
                            'qa_email' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('', 'docdirect'),
                                'desc' => esc_html__('This template will be used to send an email to provider who get question on their profiles.', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'html' => '',
                            ),
                            'qa_subject' => array(
                                'type' => 'text',
                                'value' => 'A question has posted on your profile.',
                                'label' => esc_html__('Subject', 'docdirect'),
                                'desc' => esc_html__('Please add subject for email', 'docdirect'),
                            ),
                            'qa_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'attr' => array(),
                                'label' => esc_html__('Email Settings variables', 'docdirect'),
                                'desc' => esc_html__('', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'html' => '%name% — To display provider name who will get questions.<br/>
											%question% — To display question title.<br/>
                                            %logo% — To display site logo.<br/>',
                            ),
                            'qa_content' => array(
                                'type' => 'wp-editor',
                                'value' => 'Hi %name%,<br/>
                                    A person has posted a question at your profile, question is given below.<br/><br/>
                                    
									%question%

                                    <br/>
                                    %logo%',
                                
                                'attr' => array(),
                                'label' => esc_html__('Email Contents', 'docdirect'),
                                'desc' => esc_html__('', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'size' => 'large', // small, large
                                'editor_height' => 400,
                            )
                        )
                    ),
					'answer_email' => array(
                        'title' => esc_html__('Answers', 'docdirect'),
                        'type' => 'tab',
                        'options' => array(
                            'answer_email' => array(
                                'type' => 'html',
                                'value' => '',
                                'label' => esc_html__('', 'docdirect'),
                                'desc' => esc_html__('This template will be used to send an email to the question author', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'html' => '',
                            ),
                            'answer_subject' => array(
                                'type' => 'text',
                                'value' => 'New answer posted on your question.',
                                'label' => esc_html__('Subject', 'docdirect'),
                                'desc' => esc_html__('Please add subject for email', 'docdirect'),
                            ),
                            'answer_info' => array(
                                'type' => 'html',
                                'value' => '',
                                'attr' => array(),
                                'label' => esc_html__('Email Settings variables', 'docdirect'),
                                'desc' => esc_html__('', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'html' => '%name% — To display user name of question author.<br/>
											%username% — To display user name who post question.<br/>
											%question% — To display question title against which answers is posted.<br/>
											%link% — To display question detail page link.<br/>
                                            %logo% — To display site logo.<br/>',
                            ),
                            'answer_content' => array(
                                'type' => 'wp-editor',
                                'value' => 'Hi %name%,<br/>
                                    A person with name "%username%" has posted an answers on your question ( %question% ), To view all answers please follow given below link<br/><br/>
                                    
									%link%

                                    <br/>
                                    %logo%',
                                
                                'attr' => array(),
                                'label' => esc_html__('Email Contents', 'docdirect'),
                                'desc' => esc_html__('', 'docdirect'),
                                'help' => esc_html__('', 'docdirect'),
                                'size' => 'large', // small, large
                                'editor_height' => 400,
                            )
                        )
                    ),
				)
			),
			'admin_settings' => array(
				'type' => 'tab',
				'title' => esc_html__('Admin Templates', 'docdirect'),
				'options' => array(
					'admin_email' => array(
						'title' => esc_html__('Admin Email Content - Registration', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'admin_email_section' => array(
								'type' => 'html',
								'html' => esc_html__('Admin Email', 'docdirect'),
								'label' => esc_html__('', 'docdirect'),
								'desc' => esc_html__('This email will be sent to admin when new user register on your site.', 'docdirect'),
								'help' => esc_html__('', 'docdirect'),
								'images_only' => true,
							),
							'admin_register_subject' => array(
								'type' => 'text',
								'value' => 'New Registration!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Add email subject.', 'docdirect'),
							),
							'admin_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => esc_html__('Admin email address', 'docdirect'),
								'desc' => esc_html__('Please add admin email address, leave it empty to get email address from WordPress Settings.', 'docdirect'),
							),
							'admin_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%username% — To display new registered  username. <br/>
								%link% — To display the new registered user profile page at admin site.<br/>
								%email% — To display the username for login.<br/>
								%logo% — To display site logo.<br/>',
							),
							'admin_register_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hey<br/>

											A new user "%username%" with email address "%email%" has registered on your website. Please login to check user detail.
											<br/>
											You can check user detail at: %link%<br/><br/><br/>

											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,

								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
					'claim' => array(
						'title' => esc_html__('Claim/Remprt Email', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'claim_admin_email' => array(
								'type' => 'text',
								'value' => 'info@yourdomain.com',
								'label' => esc_html__('Admin email address to send claim/report email', 'docdirect'),
								'desc' => esc_html__('Please add admin email address, leave it empty to get email address from WordPress Settings.', 'docdirect'),
							),
							'claim_subject' => array(
								'type' => 'text',
								'value' => 'A user has claimed!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add subject for email', 'docdirect'),
							),
							'claim_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%claimed_user% — To display the username who has claimed. <br/>
								%claimed_by% — To display the username who has claimed. <br/>
								%message% — To display message of visitor user.<br/>
								%logo% — To display site logo(Optional)<br/>',

							),
							'claim_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hi,<br/>
											%claimed_user% has claimed by %claimed_by%
											<br/><br/>
											Message is given below.
											<br/>
												%message%
											<br/><br/>
											Sincerely,<br/>
											DocDirect Team<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'delete_account' => array(
						'title' => esc_html__('Delete Account', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'delete_hint' => array(
								'type' => 'html',
								'html' => esc_html__('Admin Email', 'docdirect'),
								'label' => esc_html__('', 'docdirect'),
								'desc' => esc_html__('This email will be sent to admin when any provider/user delete their account.', 'docdirect'),
								'help' => esc_html__('', 'docdirect'),
							),
							'delete_subject' => array(
								'type' => 'text',
								'value' => 'Account Delete',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Add email subject.', 'docdirect'),
							),
							'delete_email' => array(
								'type' => 'text',
								'value' => 'info@domain.com',
								'label' => esc_html__('Admin email address', 'docdirect'),
								'desc' => esc_html__('Please add admin email address, leave it empty to get email address from WordPress Settings.', 'docdirect'),
							),
							'delete_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%reason% — Reason to leave  account.<br/>
								%username% — Username of deleted user.<br/>
								%email% — Email address of deleted users.<br/>
								%logo% — To display site logo.<br/>',
							),
							'delete_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hi,<br/>

											An existing user has deleted account due to following reason: 
											<br/>
											%reason%
											<br/>
											
											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'article_approval' => array(
						'title' => esc_html__('Article Needs Approval', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'article_hint' => array(
								'type' => 'html',
								'html' => esc_html__('Admin Email', 'docdirect'),
								'label' => esc_html__('', 'docdirect'),
								'desc' => esc_html__('This email will be sent to admin when any provider/user post an article. This email will only be sent when article settings will be set as needs approval from admin: Theme Settings > Directory Settings > Article Approval', 'docdirect'),
								'help' => esc_html__('', 'docdirect'),
							),
							'article_subject' => array(
								'type' => 'text',
								'value' => 'Article Needs Approval',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Add email subject.', 'docdirect'),
							),
							'article_email' => array(
								'type' => 'text',
								'value' => 'info@domain.com',
								'label' => esc_html__('Admin email address', 'docdirect'),
								'desc' => esc_html__('Please add admin email address, leave it empty to get email address from WordPress Settings.', 'docdirect'),
							),
							'article_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%username% — Display username who publish article.<br/>
								%article_name% — Display article name.<br/>
								%link% — Article edit link on admin side.<br/>
								%logo% — To display site logo.<br/>',
							),
							'article_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hi,<br/>

											A provider has publish an article with the name "%article_name%" and needs approval. below is link to approve.
											<br/>
											<br/>
											<a style="color: #fff; padding: 0 50px; margin: 0 0 15px; font-size: 20px; font-weight: 600; line-height: 60px; border-radius: 8px; background: #5dc560; vertical-align: top; display: inline-block;" href="%link%">Approve</a>

											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
				)
			),
			'provider_settings' => array(
				'type' => 'tab',
				'title' => esc_html__('Provider Templates', 'docdirect'),
				'options' => array(
					'provider_appointment' => array(
						'title' => esc_html__('New Appointment', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'appointment_email'     => array(
								'type'  => 'html',
								'value' => '',
								'label' => esc_html__('', 'docdirect'),
								'desc'  => esc_html__('This template will be used for professional users to send confirmation email for appointments.', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '',
							),
							'appointment_subject' => array(
								'type' => 'text',
								'value' => 'A new Appointment has received!',
								'label' => esc_html__('Subject', 'docdirect'),
								'desc' => esc_html__('Please add subject for email', 'docdirect'),
							),
							'appointment_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%logo% — To display site logo.<br/>
											%user_from% — To display username, who request for appointment.<br/>
								',

							),
							'appointment_content' => array(
								'type'  => 'wp-editor',
								'value' => 'Hello<br/>

											This is confirmation that you have received a new appointment from %user_from%.<br/>
											To view detail please login to your dashboard and check it<br/><br/>

											Thank you<br/><br/>

											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,
							)
						)
					),
					'confirm_booking' => array(
						'title' => esc_html__('Confirm Booking', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'confirm_email'     => array(
								'type'  => 'html',
								'value' => '',
								'label' => esc_html__('', 'docdirect'),
								'desc'  => esc_html__('This template for new registered providers. This will be default content and provider can change this text in  their dashboard.', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '',
							),
							'confirm_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%customer_name% — To display customer name.<br/>
											%service% — To display appointment service.<br/>
											%provider% — To display provider name.<br/>
											%logo% — To display Logo.<br/>
								',

							),
							'confirm_booking' => array(
								'type'  => 'wp-editor',
								'value' => 'Hey %customer_name%!<br/>

											This is confirmation that you have booked "%service%"<br/> with %provider%
											We will let your know regarding your booking soon.<br/><br/>

											Thank you for choosing our company.<br/><br/>

											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,

								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
					'approve_booking' => array(
						'title' => esc_html__('Approve Booking', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'approve_email'     => array(
								'type'  => 'html',
								'value' => '',
								'label' => esc_html__('', 'docdirect'),
								'desc'  => esc_html__('This template for new registered providers. This will be default content and provider can change this text in  their dashboard.', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '',
							),
							'approve_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%customer_name% — To display customer name.<br/>
											%service% — To display appointment service.<br/>
											%provider% — To display provider name.<br/>
											%address% — To display Logo.<br/>
											%appointment_date% — Appointment Date<br/>
											%appointment_time% — Appointment Time.<br/>
											%logo% — To display Logo.<br/>
								',

							),
							'approve_booking' => array(
								'type'  => 'wp-editor',
								'value' => 'Hey %customer_name%!<br/>

											This is confirmation that your booking regarding "%service%" with %provider% has approved.<br/>

											We are waiting you at "%address%" on %appointment_date% at %appointment_time%.<br/><br/><br/>

											Sincerely,<br/>
											%logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,

								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
					'cancel_booking' => array(
						'title' => esc_html__('Cancel Booking', 'docdirect'),
						'type' => 'tab',
						'options' => array(
							'cancel_email'     => array(
								'type'  => 'html',
								'value' => '',
								'label' => esc_html__('', 'docdirect'),
								'desc'  => esc_html__('This template for new registered providers. This will be default content and provider can change this text in  their dashboard.', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '',
							),
							'cancel_info' => array(
								'type'  => 'html',
								'value' => '',
								'attr'  => array(),
								'label' => esc_html__('Email Settings variables', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'html'  => '%customer_name% — To display customer name.<br/>
											%service% — To display appointment service.<br/>
											%provider% — To display provider name.<br/>
											%logo% — To display Logo.<br/>
								',

							),
							'cancel_booking' => array(
								'type'  => 'wp-editor',
								'value' => 'Hi %customer_name%!<br/>

											 This is confirmation that your booking regarding "%service%" with %provider% has cancelled.<br/>

											 We are very sorry to process your booking right now.<br/><br/>

											 Sincerely,<br/>
											 %logo%
											',
								'attr'  => array(),
								'label' => esc_html__('Email Contents', 'docdirect'),
								'desc'  => esc_html__('', 'docdirect'),
								'help'  => esc_html__('', 'docdirect'),
								'size' => 'large', // small, large
								'editor_height' => 400,

								/**
								 * Also available
								 * https://github.com/WordPress/WordPress/blob/4.4.2/wp-includes/class-wp-editor.php#L80-L94
								 */
							)
						)
					),
				)
			),
		)
    )
);



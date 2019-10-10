<?php
/**
 * Email Footer
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/emails/email-footer.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates/Emails
 * @version 3.7.0
 */

defined( 'ABSPATH' ) || exit;
?>
															</div>
                                                            <p id="meta-bottom">For any other queries please email <a href="mailto:help@kenex.co.uk">help@kenex.co.uk</a></p>
														</td>
													</tr>
												</table>
                                                <img width="600" style="margin: 0; padding: 0;" src="<?php echo wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ?>"
												<!-- End Content -->
											</td>
										</tr>
									</table>
									<!-- End Body -->
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>

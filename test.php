<div class="description-detail box-border-ct tab-content">
    <div class="tab-pane fade" id="description">
        <div class="product-description">
            <span class="title">Description</span> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Expedita molestiae maiores fugit eveniet adipisci. Porro, tenetur placeat itaque veritatis maiores nostrum quidem vero ipsa magnam accusamus nisi natus dolorem ullam omnis hic fuga quae repellendus eum molestias debitis quibusdam. Similique, quaerat vero non neque alias fuga. Officia, autem, deleniti atque eos accusantium pariatur nihil totam cupiditate eligendi non. Rem, esse, inventore, pariatur, praesentium consequatur harum reiciendis ratione aperiam nihil laborum optio iure distinctio quos adipisci labore? Provident, maiores sit id inventore omnis minima incidunt totam. A, est, eos, repellat amet possimus perferendis delectus facilis debitis veniam commodi minus corporis minima tempore iusto cumque dolore voluptas ea vero unde quos? Praesentium, obcaecati, maiores modi perferendis dignissimos aliquam voluptates natus odio omnis placeat numquam dolore repudiandae rem nemo facere nisi voluptatibus dolores porro! Esse, quod, recusandae, odio ipsa id quos voluptas iste excepturi possimus iure maxime earum cupiditate omnis aliquid temporibus doloribus aut ipsam nam sunt mollitia necessitatibus unde odit error eos doloremque distinctio ullam tenetur repudiandae quas. Labore, sit, totam, odio, id repellendus placeat laboriosam quibusdam perferendis dolorem consequuntur quaerat accusamus explicabo blanditiis aperiam veniam delectus mollitia quod quos debitis dolor aut ea cupiditate amet provident rerum. Aspernatur, possimus, perferendis, enim, et accusamus quidem perspiciatis beatae vero quos ipsum culpa provident in. Facere, modi, iste doloribus aut vitae ullam quos consequuntur nulla excepturi necessitatibus non consequatur dolorum magni ab accusamus praesentium quibusdam! Commodi, atque, aliquid, sit tempora iusto quis quo alias vero quod itaque recusandae deserunt eligendi omnis fugiat amet? Distinctio, vel placeat molestias at sunt quis dolores! Maiores, sapiente, quas, sint dolore repudiandae in porro quidem voluptatibus temporibus ex repellat aliquam odit provident cum iusto minus ab ut ullam ipsa cumque culpa reprehenderit quos! Laudantium, fugiat, dolorum, in, unde minima soluta obcaecati suscipit saepe aut magni tenetur perferendis debitis rem quos ullam ab id animi quisquam maxime autem a sunt facilis molestiae incidunt nihil similique provident inventore. Quasi, architecto obcaecati tempora at nesciunt dicta placeat error perspiciatis tenetur laudantium! Alias, earum sunt consequatur commodi minima repellat aut numquam ducimus totam quasi! Laborum, ab, velit. Maiores, cumque, tempora, accusantium quibusdam odit nostrum sit provident delectus debitis ducimus perferendis omnis ipsum asperiores illum nemo dolorum incidunt vero deleniti nulla facilis hic esse quos ex numquam non rem unde velit vitae enim dignissimos repellendus ab quae inventore dolor porro ratione aspernatur nisi architecto ipsa iste sint adipisci iusto quisquam sapiente dicta quidem! Assumenda, vitae veritatis tenetur eius recusandae atque officia ullam laborum tempora reiciendis. Consequuntur, dolorum, praesentium iste mollitia quae saepe tempora magni necessitatibus placeat dicta adipisci quasi harum omnis doloribus iusto hic voluptas maiores quibusdam deserunt sequi aperiam voluptatem cumque vero voluptate ut natus labore commodi. Quasi, rem, totam, asperiores ut nam dolores laborum est similique accusamus cupiditate voluptates doloribus labore blanditiis aspernatur earum eius facilis! Libero, rem, id, recusandae blanditiis omnis dignissimos eum laborum nostrum obcaecati possimus inventore odit quibusdam velit voluptas numquam quas consequatur voluptatem molestiae accusantium aliquam ex eligendi autem ab est aperiam cumque facilis amet unde magnam. Animi! </div>
    </div>
    <div class="tab-pane fade active in" id="review">
        <div class="customer-reviews">
            <form id="reviewform" name="reviewForm" action="/meadows-php/index.php?option=com_virtuemart&amp;view=productdetails&amp;virtuemart_product_id=1379&amp;virtuemart_category_id=18&amp;Itemid=199" method="post">


                <h4>Submit Review<span>Be the first to write a review...</span>                </h4>
                <span class="step">First: Rate the product. Please select a rating between 0 (poorest) and 5 stars (best).</span>
                <div class="rating">
                    <label for="vote">
                        <span style="display:inline-block;width:120px;" class="vmicon ratingbox" title="Rating: 5/5">
                            <span style="width:120px" class="stars-orange">
                            </span>
                        </span></label>
                    <input type="hidden" name="vote" value="5" id="vote">
                </div>

                <div class="write-reviews">

                    <span class="step">Now please write a (short) review....(min. 0, max. 2000 characters) </span>
                    <br>
                    <textarea cols="60" rows="5" name="comment" onkeyup="refresh_counter();" onfocus="refresh_counter();" onblur="refresh_counter();" id="comment" title="Submit Review" class="virtuemart"></textarea>
                    <br>
                    <span>Characters written:                         <input type="text" readonly="" maxlength="4" name="counter" size="4" value="0">
                    </span>
                    <br><br>
                    <input type="submit" value="Submit Review" title="Submit Review" name="submit_review" onclick="return(check_reviewform());" class="abtn">

                </div>
                <input type="hidden" value="1379" name="virtuemart_product_id">
                <input type="hidden" value="com_virtuemart" name="option">
                <input type="hidden" value="18" name="virtuemart_category_id">
                <input type="hidden" value="0" name="virtuemart_rating_review_id">
                <input type="hidden" value="review" name="task">
            </form>

            <div class="list-review-det-pg">
                <h4>Reviews</h4>

                <div class="list-reviews">
                    <span class="step">There are yet no reviews for this product.</span>
                    <div class="clear"></div>
                </div>
            </div>      



        </div> 







        <script type="text/javascript" id="updDynamicListeners_js">//&lt;![CDATA[ 
            jQuery(document).ready(function() { // GALT: Start listening for dynamic content update.
            // If template is aware of dynamic update and provided a variable let's
            // set-up the event listeners.
            if (Virtuemart.container)
                    Virtuemart.updateDynamicUpdateListeners();
            }); //]]&gt;
        </script>

        <script type="text/javascript" id="updateChosen_js">//&lt;![CDATA[ 
            if (typeof Virtuemart === "undefined")
                    var Virtuemart = {};
            Virtuemart.updateChosenDropdownLayout = function() {
            var vm2string = {editImage: 'edit image', select_all_text: 'Select all', select_some_options_text: 'Available for all'};
            jQuery(function($) {
            jQuery(".vm-chzn-select").chosen({enable_select_all: true, select_all_text : vm2string.select_all_text, select_some_options_text:vm2string.select_some_options_text, disable_search_threshold: 5});
            });
            }
            Virtuemart.updateChosenDropdownLayout(); //]]&gt;
        </script>

        <script type="text/javascript" id="jsVars_js">//&lt;![CDATA[ 
            vmSiteurl = 'http://localhost/meadows-php/';
            vmLang = "";
            Virtuemart.addtocart_popup = '1';
            usefancy = true; //]]&gt;
        </script>
        <script type="text/javascript" id="ready.vmprices_js">//&lt;![CDATA[ 
            jQuery(document).ready(function($) {
            Virtuemart.product(jQuery("form.product"));
            /*$("form.js-recalculate").each(function(){
             if ($(this).find(".product-fields").length &amp;&amp; !$(this).find(".no-vm-bind").length) {
             var id= $(this).find('input[name="virtuemart_product_id[]"]').val();
             Virtuemart.setproducttype($(this),id);
     
             }
             });*/
            }); //]]&gt;
        </script>
        <script type="text/javascript" id="popups_js"> //&lt;![CDATA[
            jQuery(document).ready(function($) {

            $('a.ask-a-question, a.printModal, a.recommened-to-friend, a.manuModal').click(function(event){
            event.preventDefault();
            $.fancybox({
            href: $(this).attr('href'),
                    type: 'iframe',
                    height: 550
            });
            });
            });
        //]]&gt; </script>
        <script type="text/javascript" id="imagepopup_js">//&lt;![CDATA[ 
            jQuery(document).ready(function() {
            Virtuemart.updateImageEventListeners()
            });
            Virtuemart.updateImageEventListeners = function() {
            jQuery("a[rel=vm-additional-images]").fancybox({
            "titlePosition" 	: "inside",
                    "transitionIn"	:	"elastic",
                    "transitionOut"	:	"elastic"
            });
            jQuery(".additional-images a.product-image.image-0").removeAttr("rel");
            jQuery(".additional-images img.product-image").click(function() {
            jQuery(".additional-images a.product-image").attr("rel", "vm-additional-images");
            jQuery(this).parent().children("a.product-image").removeAttr("rel");
            var src = jQuery(this).parent().children("a.product-image").attr("href");
            jQuery(".main-image img").attr("src", src);
            jQuery(".main-image img").attr("alt", this.alt);
            jQuery(".main-image a").attr("href", src);
            jQuery(".main-image a").attr("title", this.alt);
            jQuery(".main-image .vm-img-desc").html(this.alt);
            });
            } //]]&gt;
        </script>
        <script type="text/javascript" id="rating_stars_js">//&lt;![CDATA[ 
            jQuery(function($) {
            var steps = 5;
            var parentPos = $('.rating .ratingbox').position();
            var boxWidth = $('.rating .ratingbox').width(); // nbr of total pixels
            var starSize = (boxWidth / steps);
            var ratingboxPos = $('.rating .ratingbox').offset();
            jQuery('.rating .ratingbox').mousemove(function(e){
            var span = jQuery(this).children();
            var dif = e.pageX - ratingboxPos.left; // nbr of pixels
            difRatio = Math.floor(dif / boxWidth * steps) + 1; //step
            span.width(difRatio * starSize);
            $('#vote').val(difRatio);
            //console.log('note = ',parentPos, boxWidth, ratingboxPos);
            });
            }); //]]&gt;
        </script>
        <script type="text/javascript" id="check_reviewform_js">//&lt;![CDATA[ 
            function check_reviewform() {

            var form = document.getElementById('reviewform');
            var ausgewaehlt = false;
            if (form.comment.value.length & lt; 0) {
            alert('Please write down some more words for your review. Minimum characters allowed: 0');
            return false;
            }
            else if (form.comment.value.length & gt; 2000) {
            alert('Please shorten your review. Maximum characters allowed: 2000');
            return false;
            }
            else {
            return true;
            }
            }

            function refresh_counter() {
            var form = document.getElementById('reviewform');
            form.counter.value = form.comment.value.length;
            } //]]&gt;
        </script>
        <script type="text/javascript" id="ajaxContent_js">//&lt;![CDATA[ 
    Virtuemart.container = jQuery('.productdetails-view');
    Virtuemart.containerSelector = '.productdetails-view'; //]]&gt;
        </script>
    </div>
</div>
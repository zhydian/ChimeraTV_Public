<?php
// PromotionModal class
//
// author: Alex Onorati
// This class contains all the legal queries on the database property_serpent.
class PromotionModel
{
    protected $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    //This retrives all promotions that are stored.
    public function getAllPromotions()
    {
        $sql = "SELECT
                promotion.promotion_id as promo_id,
                promotion_type.title as promo_title,
                promotion_type.image as promo_image
              FROM
                promotion, promotion_type
              WHERE
                promotion.promotion_type_id = promotion_type.id;
              ";
        $result = $this->db->prepare($sql);
        $result->execute();
        $promoResult = $result->fetchAll(PDO::FETCH_ASSOC);
        return $promoResult;
    }

    public function getPromotionProperties()
    {
        $sql = "SELECT * FROM property;";
        $result = $this->db->prepare($sql);
        $result->execute();
        $promoResult = $result->fetchAll(PDO::FETCH_ASSOC);
        return $promoResult;
    }

    public function getPromotionModelName($promtionTypeId)
    {
        $sql = "SELECT * FROM promotion_type WHERE promotion_type_id = :id";
        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $promtionTypeId, PDO::PARAM_STR);
        $result->execute();
        $promoResult = $result->fetch();
        return $promoResult['promotion_type_class_name'];
    }

    public function getAllPromotionsByProperty($propertyId)
    {
        $sql = "SELECT
                      promotion.promotion_id as promo_id,
                      promotion.artifact as artifact,
                      promotion_type.promotion_type_id as promo_type_id,
                      promotion_type.promotion_type_title as promo_title,
                      promotion_type.promotion_type_image as promo_image,
                      promotion_type.promotion_type_file_name as file_name,
                      promo_property.promo_property_property_id as property_id
                    FROM
                      promotion, promotion_type, promo_property, property
                    WHERE
                      promotion.promotion_type_id = promotion_type.promotion_type_id
                      AND  promotion.promotion_id = promo_property.promo_property_promo_id
                      AND property.property_id = promo_property.promo_property_property_id
                      AND promotion.promotion_visible = 1 AND property.property_id =  :id;
                    ";
        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $propertyId, PDO::PARAM_STR);
        $result->execute();
        $promoResult = $result->fetchAll(PDO::FETCH_ASSOC);
        return $promoResult;
    }

    public function getPromotionTypes($propertyId)
    {
        $sql = "SELECT
               promotion_type.promotion_type_id as promo_id,
               promotion_type.promotion_type_title as promo_title,
               promotion_type.promotion_type_image as promo_image,
               promotion_type.promotion_type_file_name as file_name
             FROM
               promotion_type, subscription
             WHERE
               promotion_type.promotion_type_id = subscription.promotion_type_id AND
               subscription.property_id = :propertyId
               ;";
        $result = $this->db->prepare($sql);
        $result->bindValue(':propertyId', $propertyId);
        $result->execute();
        $promoResult = $result->fetchAll(PDO::FETCH_ASSOC);
        return $promoResult;
    }

    public function addPromotion($promotionTypeId, $propertyId, $sceneId)
    {
        $sql = "INSERT INTO promotion (promotion_type_id, artifact, promotion_sceneid) VALUES (:id, :artifact, :sceneId);";
        $artifact = $this->getRandomFontAwesome();
        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $promotionTypeId, PDO::PARAM_STR);
        $result->bindValue(':artifact', $artifact, PDO::PARAM_STR);
        $result->bindValue(':sceneId', $sceneId, PDO::PARAM_STR);
        $result->execute();
        $promotionId = $this->db->lastInsertId();
        $sql = "INSERT INTO promo_property (promo_property_property_id, promo_property_promo_id) VALUES (:propertyId,:promotionId);";
        $result = $this->db->prepare($sql);
        $result->bindValue(':propertyId', $propertyId, PDO::PARAM_STR);
        $result->bindValue(':promotionId', $promotionId, PDO::PARAM_STR);
        $result->execute();

        $sql = "SELECT promotion_type.promotion_type_id as promo_type_id, 
                promotion_type.promotion_type_title as promo_type_id, 
                promotion_type.promotion_type_image as promo_image, 
                promotion.artifact, promotion_type.promotion_type_file_name as file_name
                FROM promotion, promotion_type 
                WHERE promotion_type.promotion_type_id = promotion.promotion_type_id 
                AND promotion.promotion_id = :id;
                ";

        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $promotionId, PDO::PARAM_STR);
        $result->execute();
        $promoResult = $result->fetch(PDO::FETCH_ASSOC);
        $promoResult['promo_id'] = $promotionId;
        $promoResult['property_id'] = $propertyId;
        return $promoResult;
    }

    public function getPromotionImageByPromotionType($id)
    {
        $sql = "SELECT promotion_type_image as image FROM promotion_type WHERE promotion_type_id = :id;";
        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $id, PDO::PARAM_STR);
        $result->execute();
        $promoResult = $result->fetch(PDO::FETCH_ASSOC);
        return $promoResult['image'];
    }

    public function getPromotionImageByPromotionId($id)
    {
        $sql = "SELECT promotion_type_image as image FROM promotion, promotion_type
                WHERE promotion.promotion_id =" . $id . " AND promotion_type.promotion_type_id = promotion.promotion_type_id";
        $result = $this->db->prepare($sql);
        $result->execute();
        $image = $result->fetch(PDO::FETCH_ASSOC);
        return $image;
    }

    function getRandomFontAwesome()
    {
        $artifactList = $this->generateFontAwesomeArray();
        $artifactSelection = rand(0, sizeof($artifactList));
        return $artifactList[$artifactSelection];
    }

    public function generateFontAwesomeArray()
    {
        return $fontawesome = array('fa-glass', 'fa-music', 'fa-search', 'fa-envelope-o', 'fa-heart', 'fa-star', 'fa-star-o', 'fa-user', 'fa-film', 'fa-th-large', 'fa-th', 'fa-th-list', 'fa-check', 'fa-remove', 'fa-close', 'fa-times', 'fa-search-plus', 'fa-search-minus', 'fa-power-off', 'fa-signal', 'fa-gear', 'fa-cog', 'fa-trash-o', 'fa-home', 'fa-file-o', 'fa-clock-o', 'fa-road', 'fa-download', 'fa-arrow-circle-o-down', 'fa-arrow-circle-o-up', 'fa-inbox', 'fa-play-circle-o', 'fa-rotate-right', 'fa-repeat', 'fa-refresh', 'fa-list-alt', 'fa-lock', 'fa-flag', 'fa-headphones', 'fa-volume-off', 'fa-volume-down', 'fa-volume-up', 'fa-qrcode', 'fa-barcode', 'fa-tag', 'fa-tags', 'fa-book', 'fa-bookmark', 'fa-print', 'fa-camera', 'fa-font', 'fa-bold', 'fa-italic', 'fa-text-height', 'fa-text-width', 'fa-align-left', 'fa-align-center', 'fa-align-right', 'fa-align-justify', 'fa-list', 'fa-dedent', 'fa-outdent', 'fa-indent', 'fa-video-camera', 'fa-photo', 'fa-image', 'fa-picture-o', 'fa-pencil', 'fa-map-marker', 'fa-adjust', 'fa-tint', 'fa-edit', 'fa-pencil-square-o', 'fa-share-square-o', 'fa-check-square-o', 'fa-arrows', 'fa-step-backward', 'fa-fast-backward', 'fa-backward', 'fa-play', 'fa-pause', 'fa-stop', 'fa-forward', 'fa-fast-forward', 'fa-step-forward', 'fa-eject', 'fa-chevron-left', 'fa-chevron-right', 'fa-plus-circle', 'fa-minus-circle', 'fa-times-circle', 'fa-check-circle', 'fa-question-circle', 'fa-info-circle', 'fa-crosshairs', 'fa-times-circle-o', 'fa-check-circle-o', 'fa-ban', 'fa-arrow-left', 'fa-arrow-right', 'fa-arrow-up', 'fa-arrow-down', 'fa-mail-forward', 'fa-share', 'fa-expand', 'fa-compress', 'fa-plus', 'fa-minus', 'fa-asterisk', 'fa-exclamation-circle', 'fa-gift', 'fa-leaf', 'fa-fire', 'fa-eye', 'fa-eye-slash', 'fa-warning', 'fa-exclamation-triangle', 'fa-plane', 'fa-calendar', 'fa-random', 'fa-comment', 'fa-magnet', 'fa-chevron-up', 'fa-chevron-down', 'fa-retweet', 'fa-shopping-cart', 'fa-folder', 'fa-folder-open', 'fa-arrows-v', 'fa-arrows-h', 'fa-bar-chart-o', 'fa-bar-chart', 'fa-twitter-square', 'fa-facebook-square', 'fa-camera-retro', 'fa-key', 'fa-gears', 'fa-cogs', 'fa-comments', 'fa-thumbs-o-up', 'fa-thumbs-o-down', 'fa-star-half', 'fa-heart-o', 'fa-sign-out', 'fa-linkedin-square', 'fa-thumb-tack', 'fa-external-link', 'fa-sign-in', 'fa-trophy', 'fa-github-square', 'fa-upload', 'fa-lemon-o', 'fa-phone', 'fa-square-o', 'fa-bookmark-o', 'fa-phone-square', 'fa-twitter', 'fa-facebook-f', 'fa-facebook', 'fa-github', 'fa-unlock', 'fa-credit-card', 'fa-rss', 'fa-hdd-o', 'fa-bullhorn', 'fa-bell', 'fa-certificate', 'fa-hand-o-right', 'fa-hand-o-left', 'fa-hand-o-up', 'fa-hand-o-down', 'fa-arrow-circle-left', 'fa-arrow-circle-right', 'fa-arrow-circle-up', 'fa-arrow-circle-down', 'fa-globe', 'fa-wrench', 'fa-tasks', 'fa-filter', 'fa-briefcase', 'fa-arrows-alt', 'fa-group', 'fa-users', 'fa-chain', 'fa-link', 'fa-cloud', 'fa-flask', 'fa-cut', 'fa-scissors', 'fa-copy', 'fa-files-o', 'fa-paperclip', 'fa-save', 'fa-floppy-o', 'fa-square', 'fa-navicon', 'fa-reorder', 'fa-bars', 'fa-list-ul', 'fa-list-ol', 'fa-strikethrough', 'fa-underline', 'fa-table', 'fa-magic', 'fa-truck', 'fa-pinterest', 'fa-pinterest-square', 'fa-google-plus-square', 'fa-google-plus', 'fa-money', 'fa-caret-down', 'fa-caret-up', 'fa-caret-left', 'fa-caret-right', 'fa-columns', 'fa-unsorted', 'fa-sort', 'fa-sort-down', 'fa-sort-desc', 'fa-sort-up', 'fa-sort-asc', 'fa-envelope', 'fa-linkedin', 'fa-rotate-left', 'fa-undo', 'fa-legal', 'fa-gavel', 'fa-dashboard', 'fa-tachometer', 'fa-comment-o', 'fa-comments-o', 'fa-flash', 'fa-bolt', 'fa-sitemap', 'fa-umbrella', 'fa-paste', 'fa-clipboard', 'fa-lightbulb-o', 'fa-exchange', 'fa-cloud-download', 'fa-cloud-upload', 'fa-user-md', 'fa-stethoscope', 'fa-suitcase', 'fa-bell-o', 'fa-coffee', 'fa-cutlery', 'fa-file-text-o', 'fa-building-o', 'fa-hospital-o', 'fa-ambulance', 'fa-medkit', 'fa-fighter-jet', 'fa-beer', 'fa-h-square', 'fa-plus-square', 'fa-angle-double-left', 'fa-angle-double-right', 'fa-angle-double-up', 'fa-angle-double-down', 'fa-angle-left', 'fa-angle-right', 'fa-angle-up', 'fa-angle-down', 'fa-desktop', 'fa-laptop', 'fa-tablet', 'fa-mobile-phone', 'fa-mobile', 'fa-circle-o', 'fa-quote-left', 'fa-quote-right', 'fa-spinner', 'fa-circle', 'fa-mail-reply', 'fa-reply', 'fa-github-alt', 'fa-folder-o', 'fa-folder-open-o', 'fa-smile-o', 'fa-frown-o', 'fa-meh-o', 'fa-gamepad', 'fa-keyboard-o', 'fa-flag-o', 'fa-flag-checkered', 'fa-terminal', 'fa-code', 'fa-mail-reply-all', 'fa-reply-all', 'fa-star-half-empty', 'fa-star-half-full', 'fa-star-half-o', 'fa-location-arrow', 'fa-crop', 'fa-code-fork', 'fa-unlink', 'fa-chain-broken', 'fa-question', 'fa-info', 'fa-exclamation', 'fa-superscript', 'fa-subscript', 'fa-eraser', 'fa-puzzle-piece', 'fa-microphone', 'fa-microphone-slash', 'fa-shield', 'fa-calendar-o', 'fa-fire-extinguisher', 'fa-rocket', 'fa-maxcdn', 'fa-chevron-circle-left', 'fa-chevron-circle-right', 'fa-chevron-circle-up', 'fa-chevron-circle-down', 'fa-html5', 'fa-css3', 'fa-anchor', 'fa-unlock-alt', 'fa-bullseye', 'fa-ellipsis-h', 'fa-ellipsis-v', 'fa-rss-square', 'fa-play-circle', 'fa-ticket', 'fa-minus-square', 'fa-minus-square-o', 'fa-level-up', 'fa-level-down', 'fa-check-square', 'fa-pencil-square', 'fa-external-link-square', 'fa-share-square', 'fa-compass', 'fa-toggle-down', 'fa-caret-square-o-down', 'fa-toggle-up', 'fa-caret-square-o-up', 'fa-toggle-right', 'fa-caret-square-o-right', 'fa-euro', 'fa-eur', 'fa-gbp', 'fa-dollar', 'fa-usd', 'fa-rupee', 'fa-inr', 'fa-cny', 'fa-rmb', 'fa-yen', 'fa-jpy', 'fa-ruble', 'fa-rouble', 'fa-rub', 'fa-won', 'fa-krw', 'fa-bitcoin', 'fa-btc', 'fa-file', 'fa-file-text', 'fa-sort-alpha-asc', 'fa-sort-alpha-desc', 'fa-sort-amount-asc', 'fa-sort-amount-desc', 'fa-sort-numeric-asc', 'fa-sort-numeric-desc', 'fa-thumbs-up', 'fa-thumbs-down', 'fa-youtube-square', 'fa-youtube', 'fa-xing', 'fa-xing-square', 'fa-youtube-play', 'fa-dropbox', 'fa-stack-overflow', 'fa-instagram', 'fa-flickr', 'fa-adn', 'fa-bitbucket', 'fa-bitbucket-square', 'fa-tumblr', 'fa-tumblr-square', 'fa-long-arrow-down', 'fa-long-arrow-up', 'fa-long-arrow-left', 'fa-long-arrow-right', 'fa-apple', 'fa-windows', 'fa-android', 'fa-linux', 'fa-dribbble', 'fa-skype', 'fa-foursquare', 'fa-trello', 'fa-female', 'fa-male', 'fa-gittip', 'fa-gratipay', 'fa-sun-o', 'fa-moon-o', 'fa-archive', 'fa-bug', 'fa-vk', 'fa-weibo', 'fa-renren', 'fa-pagelines', 'fa-stack-exchange', 'fa-arrow-circle-o-right', 'fa-arrow-circle-o-left', 'fa-toggle-left', 'fa-caret-square-o-left', 'fa-dot-circle-o', 'fa-wheelchair', 'fa-vimeo-square', 'fa-turkish-lira', 'fa-try', 'fa-plus-square-o', 'fa-space-shuttle', 'fa-slack', 'fa-envelope-square', 'fa-wordpress', 'fa-openid', 'fa-institution', 'fa-bank', 'fa-university', 'fa-mortar-board', 'fa-graduation-cap', 'fa-yahoo', 'fa-google', 'fa-reddit', 'fa-reddit-square', 'fa-stumbleupon-circle', 'fa-stumbleupon', 'fa-delicious', 'fa-digg', 'fa-pied-piper', 'fa-pied-piper-alt', 'fa-drupal', 'fa-joomla', 'fa-language', 'fa-fax', 'fa-building', 'fa-child', 'fa-paw', 'fa-spoon', 'fa-cube', 'fa-cubes', 'fa-behance', 'fa-behance-square', 'fa-steam', 'fa-steam-square', 'fa-recycle', 'fa-automobile', 'fa-car', 'fa-cab', 'fa-taxi', 'fa-tree', 'fa-spotify', 'fa-deviantart', 'fa-soundcloud', 'fa-database', 'fa-file-pdf-o', 'fa-file-word-o', 'fa-file-excel-o', 'fa-file-powerpoint-o', 'fa-file-photo-o', 'fa-file-picture-o', 'fa-file-image-o', 'fa-file-zip-o', 'fa-file-archive-o', 'fa-file-sound-o', 'fa-file-audio-o', 'fa-file-movie-o', 'fa-file-video-o', 'fa-file-code-o', 'fa-vine', 'fa-codepen', 'fa-jsfiddle', 'fa-life-bouy', 'fa-life-buoy', 'fa-life-saver', 'fa-support', 'fa-life-ring', 'fa-circle-o-notch', 'fa-ra', 'fa-rebel', 'fa-ge', 'fa-empire', 'fa-git-square', 'fa-git', 'fa-hacker-news', 'fa-tencent-weibo', 'fa-qq', 'fa-wechat', 'fa-weixin', 'fa-send', 'fa-paper-plane', 'fa-send-o', 'fa-paper-plane-o', 'fa-history', 'fa-genderless', 'fa-circle-thin', 'fa-header', 'fa-paragraph', 'fa-sliders', 'fa-share-alt', 'fa-share-alt-square', 'fa-bomb', 'fa-soccer-ball-o', 'fa-futbol-o', 'fa-tty', 'fa-binoculars', 'fa-plug', 'fa-slideshare', 'fa-twitch', 'fa-yelp', 'fa-newspaper-o', 'fa-wifi', 'fa-calculator', 'fa-paypal', 'fa-google-wallet', 'fa-cc-visa', 'fa-cc-mastercard', 'fa-cc-discover', 'fa-cc-amex', 'fa-cc-paypal', 'fa-cc-stripe', 'fa-bell-slash', 'fa-bell-slash-o', 'fa-trash', 'fa-copyright', 'fa-at', 'fa-eyedropper', 'fa-paint-brush', 'fa-birthday-cake', 'fa-area-chart', 'fa-pie-chart', 'fa-line-chart', 'fa-lastfm', 'fa-lastfm-square', 'fa-toggle-off', 'fa-toggle-on', 'fa-bicycle', 'fa-bus', 'fa-ioxhost', 'fa-angellist', 'fa-cc', 'fa-shekel', 'fa-sheqel', 'fa-ils', 'fa-meanpath', 'fa-buysellads', 'fa-connectdevelop', 'fa-dashcube', 'fa-forumbee', 'fa-leanpub', 'fa-sellsy', 'fa-shirtsinbulk', 'fa-simplybuilt', 'fa-skyatlas', 'fa-cart-plus', 'fa-cart-arrow-down', 'fa-diamond', 'fa-ship', 'fa-user-secret', 'fa-motorcycle', 'fa-street-view', 'fa-heartbeat', 'fa-venus', 'fa-mars', 'fa-mercury', 'fa-transgender', 'fa-transgender-alt', 'fa-venus-double', 'fa-mars-double', 'fa-venus-mars', 'fa-mars-stroke', 'fa-mars-stroke-v', 'fa-mars-stroke-h', 'fa-neuter', 'fa-facebook-official', 'fa-pinterest-p', 'fa-whatsapp', 'fa-server', 'fa-user-plus', 'fa-user-times', 'fa-hotel', 'fa-bed', 'fa-viacoin', 'fa-train', 'fa-subway', 'fa-medium');
    }

    public function getPromotionsByDisplayId($displayId)
    {
        $sql = "SELECT
                      promotion.promotion_id as promo_id,
                      promotion_type.promotion_type_image,
                      promotion_type.promotion_type_file_name,
                      promotion_property.property_id,
                      promotion_property.display_id,
                      promotion_property.scene_duration,
                      promotion_property.skin_id,
                      promotion_type.promotion_type_title,
                      promotion_type.promotion_type_scene_verbage
                    FROM
                      promotion, promotion_type, promotion_property, property
                    WHERE
                       promotion_property.display_id = :id
						AND promotion_type.promotion_type_id = promotion.promotion_type_id
						AND promotion.promotion_id = promotion_property.promotion_id
                      GROUP BY promotion.promotion_id ORDER BY promotion_property.display_id;
                    ";
        $result = $this->db->prepare($sql);
        $result->bindValue(':id', $displayId);
        $result->execute();
        $assignedpromotions = $result->fetchAll(PDO::FETCH_ASSOC);
        return $assignedpromotions;
    }

    public function getUnassignedPromotions($displayId, $propertyID)
    {
        $sql = "SELECT * FROM promo_property p, promotion_type, promotion WHERE NOT EXISTS ( SELECT null FROM promotion_property d 
              WHERE d.promotion_id = p.promo_property_promo_id AND d.display_id=:display_id ) 
			  AND promotion.promotion_id = p.promo_property_promo_id
              AND p.promo_property_property_id=:property_id 
              AND promotion.promotion_type_id = promotion_type.promotion_type_id
			  GROUP BY p.promo_property_promo_id;";
        $result = $this->db->prepare($sql);
        $result->bindValue(':display_id', $displayId);
        $result->bindValue(':property_id', $propertyID);
        $result->execute();
        $unassignedPromotions = $result->fetchAll(PDO::FETCH_ASSOC);
        return $unassignedPromotions;
    }
}

?>
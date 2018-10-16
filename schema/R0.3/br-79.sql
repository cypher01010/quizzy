
CREATE TABLE IF NOT EXISTS `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `date_created` double NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;


ALTER TABLE  `news` ADD  `title` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `id` ;


ALTER TABLE  `news` ADD  `url` VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER  `content` ;


ALTER TABLE  `news` CHANGE  `date_created`  `date_created` TIMESTAMP NOT NULL ;


INSERT INTO `news` (`id`, `title`, `content`, `url`, `date_created`, `status`) VALUES (NULL, 'Digital Education in Singapore', '20 Dec 2014
<p>
Quizzy is an effective elearning platform designed to help any level of learner to master difficult concepts and contents. Read more about the current use of education technology in Singapore below.
</p>
<p>
Digital Education in Singapore
</p>
<p>
Singapore is recognized globally for its high-performing education system that has pioneered new education models and inspired other initiatives worldwide. According to IMD World Competitiveness Yearbook 2013, Singapore was ranked among top 5 in the world for its educational system and educationists credit this success partially to technology, as the city-state has indeed astutely integrated designed digital tools such as digital libraries and e-learning in its education landscape. Written by Rebecca Zay, Project Mnager at swissnex Singapore
</p>
<p>
Adapted from <a title="https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/" href="https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/">https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/</a>
</p>', 'digital-education-in-singapore', CURRENT_TIMESTAMP, '1');




UPDATE `news` SET `content` = '<p>
20 Dec 2014
</p>
<p>
Quizzy is an effective elearning platform designed to help any level of learner to master difficult concepts and contents. Read more about the current use of education technology in Singapore below.
</p>
<p>
Digital Education in Singapore
</p>
<p>
Singapore is recognized globally for its high-performing education system that has pioneered new education models and inspired other initiatives worldwide. According to IMD World Competitiveness Yearbook 2013, Singapore was ranked among top 5 in the world for its educational system and educationists credit this success partially to technology, as the city-state has indeed astutely integrated designed digital tools such as digital libraries and e-learning in its education landscape. Written by Rebecca Zay, Project Mnager at swissnex Singapore
</p>
<p>
Adapted from <a title="https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/" href="https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/">https://globalstatement.wordpress.com/2014/09/02/digital-education-in-singapore/</a>
</p>' WHERE `news`.`id` = 1;





INSERT INTO `news` (`id`, `title`, `content`, `url`, `date_created`, `status`) VALUES (NULL, 'MOE Launches Third Masterplan for ICT in Education', '31 Dec 2014

Quizzy supports MOE’s efforts to harness technology and to tranform teaching and learning. By using Quizzy, learners become self-directed and awesome learners! Read more about MOE’s directions below.

MOE Launches Third Masterplan for ICT in Education

The Ministry of Education has developed the third Masterplan for ICT in Education (2009-2014). The third masterplan represents a continuum of the vision of the first and second Masterplans i.e. to enrich and transform the learning environments of our students and equip them with the critical competencies and dispositions to succeed in a knowledge economy.

The broad strategies of the third Masterplan for ICT in Education are:
<ul>
    <li>To strengthen integration of ICT into curriculum, pedagogy and assessment to enhance learning and develop competencies for the 21st century;</li>
    <li>To provide differentiated professional development that is more practice-based and models how ICT can be effectively used to help students learn better;</li>
    <li>To improve the sharing of best practices and successful innovations; and</li>
    <li>To enhance ICT provisions in schools to support the implementation of mp3.</li>
</ul>
Read Full Press Release <a title="http://www.moe.gov.sg/media/press/2008/08/moe-launches-third-masterplan.php" href="http://www.moe.gov.sg/media/press/2008/08/moe-launches-third-masterplan.php">here</a>', 'moe-launches-third-masterplan-for-ict-in-education', CURRENT_TIMESTAMP, '1');




UPDATE `news` SET `content` = '<p>31 Dec 2014</p>

<p>Quizzy supports MOE’s efforts to harness technology and to tranform teaching and learning. By using Quizzy, learners become self-directed and awesome learners! Read more about MOE’s directions below.</p>

<p>MOE Launches Third Masterplan for ICT in Education</p>

<p>The Ministry of Education has developed the third Masterplan for ICT in Education (2009-2014). The third masterplan represents a continuum of the vision of the first and second Masterplans i.e. to enrich and transform the learning environments of our students and equip them with the critical competencies and dispositions to succeed in a knowledge economy.</p>

<p>
The broad strategies of the third Masterplan for ICT in Education are:
<ul>
    <li>To strengthen integration of ICT into curriculum, pedagogy and assessment to enhance learning and develop competencies for the 21st century;</li>
    <li>To provide differentiated professional development that is more practice-based and models how ICT can be effectively used to help students learn better;</li>
    <li>To improve the sharing of best practices and successful innovations; and</li>
    <li>To enhance ICT provisions in schools to support the implementation of mp3.</li>
</ul>
Read Full Press Release <a title="http://www.moe.gov.sg/media/press/2008/08/moe-launches-third-masterplan.php" href="http://www.moe.gov.sg/media/press/2008/08/moe-launches-third-masterplan.php">here</a>
</p>' WHERE `news`.`id` = 2;

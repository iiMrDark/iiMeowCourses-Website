<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tele: @iiMrDark - eCCPT</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
        <style>
            .accordion-content {
                transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
                overflow: hidden;
                max-height: 0;
                opacity: 0;
            }
            .accordion-content.open {
                max-height: fit-content;
                opacity: 1;
            }

            #videoList::-webkit-scrollbar {
                width: 8px;
            }
            #videoList::-webkit-scrollbar-track {
                background: #1F2937;
            }
            #videoList::-webkit-scrollbar-thumb {
                background: #4B5563;
                border-radius: 4px;
            }
            #videoList::-webkit-scrollbar-thumb:hover {
                background: #6B7280;
            }

            .video-item {
                transition: background-color 0.2s ease, transform 0.2s ease;
            }
            .video-item:hover {
                transform: translateX(5px);
            }
            .video-item.active {
                background-color: #374151;
            }

            .gradient-bg {
                background: linear-gradient(135deg, #6B46C1, #805AD5);
            }
            .gradient-bg:hover {
                background: linear-gradient(135deg, #805AD5, #6B46C1);
            }
        </style>
    </head>
    <body class="bg-gray-950 text-white font-sans flex flex-col md:flex-row">
        <div class="w-full md:w-1/4 h-1/3 md:h-screen overflow-y-auto bg-gray-900 p-5 border-r border-gray-800" id="videoList">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-semibold flex items-center">
                    <i class="fas fa-film mr-2 text-purple-400"></i> Tele: @iiMrDark - eCCPT
                </h2>
            </div>
            <?php
                $baseDir = 'eCCPT';
                $sections = scandir($baseDir);

                function extractNumbers($videoName) {
                    preg_match('/\d+(\.\d+)*/', $videoName, $matches);
                    return $matches ? explode('.', $matches[0]) : [0];
                }

                function sortVideos($a, $b) {
                    $numsA = extractNumbers($a);
                    $numsB = extractNumbers($b);

                    for ($i = 0; $i < max(count($numsA), count($numsB)); $i++) {
                        $partA = $numsA[$i] ?? 0;
                        $partB = $numsB[$i] ?? 0;

                        if ($partA != $partB) {
                            return $partA - $partB;
                        }
                    }

                    return strnatcasecmp($a, $b);
                }

                foreach ($sections as $section) {
                    if ($section !== '.' && $section !== '..') {
                        echo "<div class='accordion-section mb-3'>
                                <div class='accordion-header flex items-center justify-between p-2 cursor-pointer bg-gray-800 rounded-lg hover:bg-gray-750 transition-colors duration-200' onclick='toggleAccordion(this)'>
                                    <h3 class='text-md font-medium flex items-center'>
                                        <i class='fas fa-folder mr-2 text-yellow-400'></i>" . htmlspecialchars($section) . "
                                    </h3>
                                    <i class='fas fa-chevron-down transform transition-transform duration-200 text-gray-400'></i>
                                </div>
                                <div class='accordion-content mt-2'>";

                        $videos = scandir("$baseDir/$section");
                        $videoFiles = array_filter($videos, function ($video) {
                            return pathinfo($video, PATHINFO_EXTENSION) === 'mp4';
                        });

                        usort($videoFiles, 'sortVideos');

                        foreach ($videoFiles as $video) {
                            $videoPath = "$baseDir/$section/$video";
                            $videoName = htmlspecialchars($video);
                            echo "<div class='video-item p-2 cursor-pointer hover:bg-gray-800 rounded flex items-center mb-1' onclick='playVideo(\"$videoPath\", this)'>
                                    <i class='fas fa-play-circle mr-2 text-purple-400'></i>$videoName
                                </div>";
                        }
                        echo "</div></div>";
                    }
                }
            ?>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 p-5 flex flex-col items-center justify-center bg-gray-950">
            <div class="w-full max-w-4xl">
                <video id="videoPlayer" controls class="w-full rounded-lg shadow-2xl bg-gray-900">
                    <source id="videoSource" src="" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <a id="downloadLink" class="mt-4 px-4 py-2 gradient-bg text-white rounded-lg shadow text-center w-48 flex items-center justify-center transition-all duration-200" href="#" download>
                    <i class="fas fa-download mr-2"></i> Download Video
                </a>
            </div>
        </div>

        <script>
            function playVideo(src, element) {
                const videoPlayer = document.getElementById("videoPlayer");
                const videoSource = document.getElementById("videoSource");
                const downloadLink = document.getElementById("downloadLink");

                videoSource.src = src;
                videoPlayer.load();
                videoPlayer.play();

                downloadLink.href = src;

                const videoItems = document.querySelectorAll('.video-item');
                videoItems.forEach(item => item.classList.remove('active'));
                if (element) {
                    element.classList.add('active');
                }
            }

            function toggleAccordion(header) {
                const content = header.nextElementSibling;
                const icon = header.querySelector('i.fa-chevron-down');

                content.classList.toggle('open');
                icon.classList.toggle('rotate-180');
            }

            document.addEventListener('DOMContentLoaded', () => {
                const firstVideoItem = document.querySelector('.video-item');
                if (firstVideoItem) {
                    firstVideoItem.click();
                }
            });

            const videoPlayer = document.getElementById("videoPlayer");
            videoPlayer.addEventListener('ended', () => {
                const activeVideo = document.querySelector('.video-item.active');
                if (activeVideo && activeVideo.nextElementSibling) {
                    activeVideo.nextElementSibling.click();
                }
            });
        </script>
    </body>
</html>
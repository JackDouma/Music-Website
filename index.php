<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JackWire</title>
</head>
<body>

<h1 style="color: lawngreen;">JackWire</h1>
<?php
$folderPath = 'tunes';
$files = scandir($folderPath);

// mp3 files only filter
$mp3Files = array_filter($files, function($file) {
    return pathinfo($file, PATHINFO_EXTENSION) === 'mp3';
});

// count total
$playlistTotal = count($mp3Files);

// randomize array
shuffle($mp3Files);
?>

<!-- buttons -->
<button onclick="playPlaylist()">Play Playlist</button>
<button onclick="pauseAudio()">Pause</button>
<button onclick="resumeAudio()">Resume</button>

<br><br>

<button onclick="prevAudio()">Previous</button>
<button onclick="nextAudio()">Next</button>

<p id="playlistCountID"></p>
<p id="currentSongID"></p>
<p id="timerID"></p>
<p id="pauseID"></p>

<!-- volume control -->
<label for="volumeControl">Volume: </label>
<input type="range" id="volumeControl" min="0" max="2" step="0.1" value="1" onchange="setVolume(this.value)">

<!-- list of songs -->
<table border="1">
    <thead>
        <tr>
            <th>Playlist</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($mp3Files as $mp3File) 
        {
            echo "<tr>";
            echo "<td>{$mp3File}</td>";
            echo "</tr>";
        }
        ?>
    </tbody>
</table>

<audio id="audioPlayer" controls style="display:none;" onended="playNextAudio()" onplay="updateCurrentSong()"></audio>

<script>
var audioPlayer = document.getElementById("audioPlayer");
var currentSongID = document.getElementById("currentSongID");
var volumeControl = document.getElementById("volumeControl");
var timerID = document.getElementById("timerID");
var pauseID = document.getElementById("pauseID");
var playlist = <?php echo json_encode($mp3Files); ?>;
var currentIndex = 0;
var currentSongName = "";
var playlistTotal = <?php echo $playlistTotal; ?>;

// play mp3
function playAudio(filename) 
{
    audioPlayer.src = "tunes/" + filename;
    audioPlayer.play();
    currentSongName = filename;
    updateCurrentSong();
}

// start playlist
function playPlaylist() 
{
    if (playlist.length > 0) 
    {
        currentIndex = 0;
        updatePlaylistCount(); 
        playAudio(playlist[currentIndex]);
    }
}

// plays next song after 1 ends
function playNextAudio() 
{
    currentIndex++;
    if (currentIndex < playlist.length) 
    {
        updatePlaylistCount(); 
        playAudio(playlist[currentIndex]);
    } 
    else 
    {
        currentIndex = 0;
        playAudio(playlist[currentIndex]);
    }
}

// play previous song
function prevAudio() 
{
    if (currentIndex > 0) 
    {
        currentIndex--;
        updatePlaylistCount(); 
        playAudio(playlist[currentIndex]);
    } 
    // if at start, loop to end
    else 
    {
        currentIndex = playlist.length - 1;
        updatePlaylistCount(); 
        playAudio(playlist[currentIndex]);
    }
}

// play next song
function nextAudio() 
{
    currentIndex++;
    if (currentIndex < playlist.length) 
    {
        updatePlaylistCount(); 
        playAudio(playlist[currentIndex]);
    } 
    // if at end, loop to start
    else 
    {
        currentIndex = 0;
        updatePlaylistCount(); 
        playAudio(playlist[currentIndex]);
    }
}

// pause audio
function pauseAudio() 
{
    audioPlayer.pause();
    pauseID.textContent = "PAUSED";
}

function resumeAudio() 
{
    audioPlayer.play();
    pauseID.textContent = "";
}

function updatePlaylistCount() 
{
    playlistCountID.textContent = "In Playlist | " + (currentIndex + 1) + " / " + playlistTotal;
}
function updateCurrentSong() 
{
    currentSongID.textContent = "Current song: " + currentSongName;
}
function setVolume(volume) 
{
    audioPlayer.volume = volume;
}

// update timer display
function updateTimer() 
{
    var currentTime = formatTime(audioPlayer.currentTime);
    var totalTime = formatTime(audioPlayer.duration);
    timerID.textContent = currentTime + " / " + totalTime;
}

// format time
function formatTime(time) 
{
    var minutes = Math.floor(time / 60);
    var seconds = Math.floor(time % 60);
    return padZero(minutes) + ":" + padZero(seconds);
}

// add leading zero if needed
function padZero(value) 
{
    return value < 10 ? "0" + value : value;
}

// listener
audioPlayer.addEventListener("timeupdate", updateTimer);

</script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Randomized MP3 Files</title>
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
<button onclick="stopAllAudio()">Stop</button>

<p id="playlistCountID"></p>
<p id="currentSongID"></p>

<!-- volume control -->
<label for="volumeControl">Volume: </label>
<input type="range" id="volumeControl" min="0" max="2" step="0.2" value="1" onchange="setVolume(this.value)">

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
var playlist = <?php echo json_encode($mp3Files); ?>;
var currentIndex = 0;
var currentSongName = "";
var playlistTotal = <?php echo $playlistTotal; ?>;
var inPlaylist = false;

// play mp3
function playAudio(filename) 
{
    audioPlayer.src = "tunes/" + filename;
    audioPlayer.play();
    currentSongName = filename;
    updateCurrentSong();

    if (inPlaylist == false)
    {
        removePlaylistCount();
    }
}

// start playlist
function playPlaylist() 
{
    if (playlist.length > 0) 
    {
        inPlaylist = true;
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
        // if playlist reaches end, stop playing
        stopAllAudio();
    }
}

// stops audio
function stopAllAudio() 
{
    audioPlayer.pause();
    audioPlayer.currentTime = 0;
    removeCurrentSong();
    removePlaylistCount()
}

// pause audio
function pauseAudio() 
{
    audioPlayer.pause();
}

function resumeAudio() 
{
    audioPlayer.play();
}

function updatePlaylistCount() 
{
    playlistCountID.textContent = "In Playlist | " + (currentIndex + 1) + "/" + playlistTotal;
}

function removePlaylistCount() 
{
    playlistCountID.textContent = "";
}

function updateCurrentSong() 
{
    currentSongID.textContent = "Current song: " + currentSongName;
}

function removeCurrentSong() 
{
    currentSongID.textContent = "";
}
function setVolume(volume) 
{
    audioPlayer.volume = volume;
}

</script>
</body>
</html>
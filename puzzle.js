const size = 4;
const container = document.getElementById("puzzle-container");
let emptyX = 3, emptyY = 3;

window.onload = () => {
    createTiles();
};

function createTiles() {
    for (let i = 0; i < size * size - 1; i++) {
        const tile = document.createElement("div");
        tile.className = "tile";
        tile.innerText = i + 1;

        const x = i % size;
        const y = Math.floor(i / size);

        tile.style.left = `${x * 100}px`; // ✅ fixed: position using x
        tile.style.top = `${y * 100}px`;  // ✅ fixed: position using y
        tile.style.backgroundPosition = `-${x * 100}px -${y * 100}px`;

        tile.dataset.x = x;
        tile.dataset.y = y;

        tile.addEventListener("click", () => moveTile(tile));
        tile.addEventListener("mouseover", () => updateHover(tile));
        tile.addEventListener("mouseout", () => tile.classList.remove("movablepiece"));

        container.appendChild(tile);
    }
}

function moveTile(tile) {
    const x = parseInt(tile.dataset.x);
    const y = parseInt(tile.dataset.y);

    if ((Math.abs(x - emptyX) === 1 && y === emptyY) || (Math.abs(y - emptyY) === 1 && x === emptyX)) {
        tile.style.left = `${emptyX * 100}px`;
        tile.style.top = `${emptyY * 100}px`;

        tile.dataset.x = emptyX;
        tile.dataset.y = emptyY;

        emptyX = x;
        emptyY = y;

        if (checkWin()) {
            setTimeout(() => {
                const name = prompt("🎉 You solved the puzzle! Enter your name to save your score:");
                if (name) {
                    submitScore(name, 100); 
                }
            }, 200);
        }
    }
}

function updateHover(tile) {
    const x = parseInt(tile.dataset.x);
    const y = parseInt(tile.dataset.y);

    if ((Math.abs(x - emptyX) === 1 && y === emptyY) || (Math.abs(y - emptyY) === 1 && x === emptyX)) {
        tile.classList.add("movablepiece");
    }
}

function shufflePuzzle() {
    let moves = 300;
    while (moves > 0) {
        const directions = [
            { dx: 1, dy: 0 },
            { dx: -1, dy: 0 },
            { dx: 0, dy: 1 },
            { dx: 0, dy: -1 }
        ];
        const validMoves = [];

        directions.forEach(d => {
            const newX = emptyX + d.dx;
            const newY = emptyY + d.dy;

            const tile = [...container.children].find(t =>
                parseInt(t.dataset.x) === newX && parseInt(t.dataset.y) === newY
            );

            if (tile) validMoves.push(tile);
        });

        const tile = validMoves[Math.floor(Math.random() * validMoves.length)];
        moveTile(tile);
        moves--;
    }
}

function checkWin() {
    const tiles = document.querySelectorAll('.tile');
    for (let i = 0; i < tiles.length; i++) {
        if (tiles[i].textContent !== (i + 1).toString()) {
            return false;
        }
    }
    return true;
}

function submitScore(name, score) {
    fetch("save_score.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ name, score })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            alert(`🎉 Score saved for ${name}!`);
        } else {
            alert(`⚠️ Error saving score: ${data.message}`);
        }
    })
    .catch(error => {
        console.error("Fetch error:", error);
        alert("⚠️ Could not connect to the server.");
    });
}

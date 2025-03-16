let sourceDir = "";

if (hostname === "localhost") {
    sourceDir = "/chl/public";
} else if (hostname === "127.0.0.1") {
    sourceDir = "";
} else {
    sourceDir = "";
}

export { sourceDir };

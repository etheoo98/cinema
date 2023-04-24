<!doctype html>
<title>CSS Grid Template 6</title>
<style>
    body {
        display: grid;
        grid-template-areas:
    "header header"
    "nav article"
    "footer footer";
        grid-template-rows: 80px 1fr 70px;
        grid-template-columns: 20% 1fr;
        grid-row-gap: 10px;
        grid-column-gap: 10px;
        height: 100vh;
        margin: 0;
    }
    header, footer, article, nav {
        padding: 1.2em;
        background: yellowgreen;
    }
    #pageHeader {
        grid-area: header;
    }
    #pageFooter {
        grid-area: footer;
    }
    #mainArticle {
        grid-area: article;
    }
    #mainNav {
        grid-area: nav;
    }
    /* Stack the layout on small devices/viewports. */
    @media all and (max-width: 768px) {
        body {
            grid-template-areas:
      "header"
      "article"
      "nav"
      "footer";
            grid-template-rows: 80px 1fr 170px 70px;
            grid-template-columns: 1fr;
        }
    }
</style>
<body>
<header id="pageHeader">Header</header>
<article id="mainArticle">
    <h1>Deployment of Cinema</h1>

    <p>This setup wizard will help you to deploy Cinema by etheoo on your own Apache Web Server and MySQL database.</p>

    <p>In order to successfully deploy this application, we recommend using a clean installation of XAMPP.</p>

    <p>
        What this setup wizard does, is that it will create a database named 'cinema' and set up the correct table
        structure in said database. If you by any chance already have a database called 'cinema', assume that it will be
        deleted and all of its contents lost.
    </p>

    <button>Setup Database</button>
</article>
<nav id="mainNav">
    <h3>Log</h3>
</nav>
<footer id="pageFooter">Footer</footer>
</body>
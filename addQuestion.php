<!DOCTYPE html>
<html>
    <head>
        <title>Add a Question</title>
        <h1>Add a Question</h1>
    </head>

    <body>
        <table>
            <form method = "post">
            <tr>
                <td>Enter the Question</td>
                <td><input type ="textarea" name = "question" placeholder ="Enter your question here" rows = "50"></textarea></td>
            </tr>
            <tr>
                 <td>Option A</td>
                 <td><textarea name = "optionA" placeholder ="Option A"></textarea></td>
            </tr>
            <tr>
                 <td>Option B</td>
                 <td><textarea name = "optionB" placeholder ="Option B"></textarea></td>
            </tr>
            <tr>
                 <td>Option C</td>
                 <td><textarea name = "optionC" placeholder ="Option C"></textarea></td>
            </tr>
            <tr>
                 <td>Option D</td>
                 <td><textarea name = "optionD" placeholder ="Option D"></textarea></td>
            </tr>
            <tr>
                <td>Please Select the Correct Answer</td>
                <td><input type = "radio" name = "answer" value = "1">
                <label for = "correctA">Option A</label><br>
                <input type = "radio" name = "answer" value = "2">
                <label for = "correctB">Option B</label><br>
                <input type = "radio" name = "answer" value = "3">
                <label for = "correctC">Option C</label><br>
                <input type = "radio" name = "answer" value = "4">
                <label for = "correctD">Option D</label><br>
                </td>
            </tr>
            </form>
        </table>
    </body>
</html>
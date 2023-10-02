@ECHO OFF

:: Reset ERRORLEVEL
VERIFY OTHER 2>nul



CALL :SetLocalEnvHelper 2>nul

ECHO SET PHP_HOME=%PHP_HOME%
ECHO.

SET PATH=%CD%\bin;%PHP_HOME%;%PATH%
GOTO END



:SetLocalEnvHelper
IF EXIST .env (
    FOR /F "eol=# tokens=1* delims==" %%i IN (.env) DO (
        SET "%%i=%%j"
        ECHO SET %%i=%%j
    )
    ECHO.
)
EXIT /B 0



:END_ERROR
EXIT /B 1

:END
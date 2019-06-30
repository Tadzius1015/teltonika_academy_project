<?php


class CityController extends Controller
{
    public function index()
    {
        $id = $_SESSION['countryId'];
        $this->activate();
        if (isset($_SESSION['filterStatus'])) {
            if (isset($_SESSION['sort'])) {
                $result = $this->choiceToGetData();
                $pagesCount = $this->pages($this->tableElementFilterCount());
                $this->view('cities', ['result' => $result, 'countryId' => $id, 'pagesCount' => $pagesCount,
                    'editStatus' => false]);
            }
            $this->filterDataByDate();
        }
        if (isset($_SESSION['searchStatus'])) {
            if (isset($_SESSION['sort'])) {
                $result = $this->choiceToGetData();
                $pagesCount = $this->pages($this->tableElementSearchedCount());
                $this->view('cities', ['result' => $result, 'countryId' => $id, 'pagesCount' => $pagesCount,
                    'editStatus' => false]);
            }
            $this->searchData();
        }
        $result = $this->choiceToGetData();
        $pagesCount = $this->pages($this->tableElementCount());
        $this->view('cities', ['result' => $result, 'countryId' => $id, 'pagesCount' => $pagesCount,
            'editStatus' => false]);
    }

    public function activate()
    {
        if(!isset($_GET['param'])) {
            $_SESSION['currentPageNumber'] = 1;
        } else {
            $_SESSION['currentPageNumber'] = intval($_GET['param']);
        }
        if (isset($_GET['sort'])) {
            $_SESSION['sort'] = $_GET['sort'];
        }
    }

    public function redirectToCountries()
    {
        include_once('HomeController.php');
        unset($_SESSION['countryId']);
        $countryObj = new HomeController();
        $this->clearFilter();
        $this->clearSearch();
        unset($_SESSION['sort']);
        unset($_SESSION['currentPageNumber']);
        $countryObj->index();
    }

    public function create()
    {
        $id = $_SESSION['countryId'];
        unset($_SESSION['filterStatus']);
        $connection = $this->connect();
        $cityName = $_POST['city_name'];
        $cityArea = $_POST['area'];
        $city_inhabitants_count = $_POST['inhabitants_count'];
        $city_postal_code = $_POST['postal_code'];
        $today_date = date('Y-m-d');
        $data = "INSERT INTO cities(name, area, inhabitants_count, postal_code, date_created, fk_countryId) 
                     VALUES ('$cityName', '$cityArea', $city_inhabitants_count, $city_postal_code, '$today_date', $id)";
        $query = $connection->query($data);
        $connection->close();
        $this->clear();
    }

    public function deleteCity()
    {
        $id = intval($_GET['id']);
        $connection = $this->connect();

        // Remove selected country cities

        $sql = "DELETE FROM cities WHERE id = '$id'";
        $query = $connection->query($sql);

        $connection->close();
        header("Location: /akademijai/public/CityController/index");
        $this->index();
    }

    public function getCityById()
    {
        $finalResult = [];
        $id = intval($_GET['id']);
        $allCities = [];
        $pagesCount = 0;
        $connection = $this->connect();
        if (isset($_SESSION['filterStatus'])) {
            $_SESSION['filterStatuss'] = true;
            $allCities = $this->filterDataByDate();
            $pagesCount = $this->pages($this->tableElementFilterCount());
        } else if (isset($_SESSION['searchStatus'])) {
            $_SESSION['searchStatuss'] = true;
            $allCities = $this->searchData();
            $pagesCount = $this->pages($this->tableElementSearchedCount());
        } else {
            $allCities = $this->choiceToGetData();
            $pagesCount = $this->pages($this->tableElementCount());
        }
        $data = "SELECT * FROM cities WHERE id = '$id'";
        $result = $connection->query($data);
        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        }
        $connection->close();
        $this->view('cities', ['city' => $finalResult, 'countryId' => $_SESSION['countryId'],
            'pagesCount' => $pagesCount, 'result' => $allCities, 'editStatus' => true]);
    }

    public function updateCity()
    {
        $id = intval($_GET['id']);
        $cityName = $_POST['city_name'];
        $cityArea = $_POST['area'];
        $city_inhabitants_count = $_POST['inhabitants_count'];
        $city_postal_code = $_POST['postal_code'];
        $connection = $this->connect();
        $sql = "UPDATE cities SET name = '$cityName', area = '$cityArea', inhabitants_count = '$city_inhabitants_count',
postal_code = '$city_postal_code' WHERE id = '$id'";
        $result = $connection->query($sql);
        $connection->close();
        $this->index();
    }

    public function choiceToGetData()
    {
        if (isset($_SESSION['sort'])) {
            $result = $this->getSortedData();
            return $result;
        } else {
            $result = $this->getAllCountryCities();
            return $result;
        }
    }

    public function getSortedData()
    {
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $countryId = $_SESSION['countryId'];
        if (isset($_SESSION['filterStatus'])) {
            $date = $_SESSION['date'];
            $sql_asc = "SELECT * FROM cities WHERE date_created = '$date' AND fk_countryId = '$countryId' 
                        ORDER BY name ASC LIMIT 10 OFFSET $offset";
            $sql_desc = "SELECT * FROM cities WHERE date_created = '$date' AND fk_countryId = '$countryId' 
                         ORDER BY name DESC LIMIT 10 OFFSET $offset";
            $finalResult = $this->sortData($sql_asc, $sql_desc);
            return $finalResult;
        } else if (isset($_SESSION['searchStatus'])) {
            $text = $_SESSION['search_text'];
            $sql_asc = "SELECT * FROM cities WHERE name LIKE '%$text%' OR area LIKE '%$text%' OR inhabitants_count 
            LIKE '%$text%' OR postal_code LIKE '%$text%' AND fk_countryId = '$countryId' ORDER BY name ASC LIMIT 10 
            OFFSET $offset";
            $sql_desc = "SELECT * FROM cities WHERE name LIKE '%$text%' OR area LIKE '%$text%' OR inhabitants_count 
            LIKE '%$text%' OR postal_code LIKE '%$text%' AND fk_countryId = '$countryId' ORDER BY name DESC LIMIT 10 
            OFFSET $offset";
            $finalResult = $this->sortData($sql_asc, $sql_desc);
            return $finalResult;
        } else {
            $sql_asc = "SELECT * FROM cities WHERE fk_countryId = '$countryId' ORDER BY name ASC LIMIT 10 OFFSET $offset";
            $sql_desc = "SELECT * FROM cities WHERE fk_countryId = '$countryId' ORDER BY name DESC LIMIT 10 OFFSET $offset";
            $finalResult = $this->sortData($sql_asc, $sql_desc);

            return $finalResult;
        }
    }

    public function sortData($sql_asc, $sql_desc)
    {
        $finalResult = [];
        $connection = $this->connect();
        if ($_SESSION['sort'] == 'asc') {
            $data = $sql_asc;
            $result = $connection->query($data);
            while ($tmp = $result->fetch_assoc()) {
                $finalResult[] = $tmp;
            }
        } else {
            $data = $sql_desc;
            $result = $connection->query($data);
            while ($tmp = $result->fetch_assoc()) {
                $finalResult[] = $tmp;
            }
        }
        $connection->close();

        return $finalResult;
    }

    public function getAllCountryCities()
    {
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $countryId = $_SESSION['countryId'];
        $finalResult = [];
        $connection = $this->connect();
        $data = "SELECT * FROM cities WHERE fk_countryId = '$countryId' LIMIT 10 OFFSET $offset";
        $result = $connection->query($data);

        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        }
        $connection->close();

        return $finalResult;
    }
    public function pages($elementsCount)
    {
        $elementsInPage = 10;
        $pagesCount = 1;
        if ($elementsCount <= $elementsInPage) {
            return $pagesCount;
        } else {
            if ($elementsCount % $elementsInPage == 0) {
                return intval($elementsCount / $elementsInPage);
            } else {
                return intval(($elementsCount / $elementsInPage) + 1);
            }
        }
    }

    public function filterDataByDate()
    {
        $this->clearSearch();
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $countryId = $_SESSION['countryId'];
        $finalResult = [];
        if (isset($_POST['date'])) {
            $_SESSION['date'] = $_POST['date'];
        }
        $convertedData = $_SESSION['date'];
        $_SESSION['filterStatus'] = true;
        $connection = $this->connect();
        $sql = "SELECT * FROM cities WHERE date_created = '$convertedData' AND fk_countryId = '$countryId' 
                LIMIT 10 OFFSET $offset";
        $result = $connection->query($sql);
        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        }
        $connection->close();
        if (isset($_SESSION['filterStatuss'])) {
            $_SESSION['result'] = $finalResult;
            unset($_SESSION['filterStatuss']);
            return $finalResult;
        } else {
            $pagesCount = $this->pages($this->tableElementFilterCount());
            $this->view('cities', ['result' => $finalResult, 'countryId' => $countryId, 'pagesCount' => $pagesCount, 'editStatus' => false]);
        }

        return 0;
    }

    public function clearFilter()
    {
        unset($_SESSION['filterStatus']);
        unset($_SESSION['filterStatuss']);
        unset($_SESSION['date']);
    }

    public function clearSearch()
    {
        unset($_SESSION['searchStatus']);
        unset($_SESSION['searchStatuss']);
        unset($_SESSION['search_text']);
    }

    public function clear()
    {
        $this->clearFilter();
        $this->clearSearch();
        unset($_SESSION['sort']);
        $this->index();
    }

    public function abortSort()
    {
        unset($_SESSION['sort']);
        $this->index();
    }

    public function connect()
    {
        $connection = mysqli_connect("localhost", "root", "", "akademijai");
        if (mysqli_connect_errno())
        {
            echo "Klaida " . mysqli_connect_error();
            die();
        }

        return $connection;
    }

    public function tableElementCount()
    {
        $countryId = $_SESSION['countryId'];
        $connection = $this->connect();
        $elementsCount = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM cities WHERE fk_countryId = '$countryId'"));

        return $elementsCount;
    }

    public function tableElementFilterCount()
    {
        $countryId = $_SESSION['countryId'];
        $convertedData = $_SESSION['date'];
        $connection = $this->connect();
        $elementsCount = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM cities WHERE date_created = '$convertedData' AND fk_countryId = '$countryId'"));

        return $elementsCount;
    }

    public function tableElementSearchedCount()
    {
        $countryId = $_SESSION['countryId'];
        $text = $_SESSION['search_text'];
        $connection = $this->connect();
        $elementsCount = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM cities WHERE name LIKE '%$text%' OR area LIKE '%$text%' OR inhabitants_count LIKE '%$text%' OR postal_code LIKE '%$text%' AND fk_countryId = '$countryId'"));

        return $elementsCount;
    }

    public function searchData()
    {
        $this->clearFilter();
        $countryId = $_SESSION['countryId'];
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $_SESSION['searchStatus'] = true;
        $finalResult = [];
        if (isset($_POST['text'])) {
            $_SESSION['search_text'] = $_POST['text'];
        }
        $text = $_SESSION['search_text'];
        $connection = $this->connect();
        $sql = "SELECT * FROM cities WHERE fk_countryId = '$countryId' AND name LIKE '%$text%' OR area LIKE '%$text%' 
                OR inhabitants_count LIKE '%$text%' OR postal_code LIKE '%$text%' LIMIT 10 OFFSET $offset";
        $result = $connection->query($sql);
        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        };
        $connection->close();
        if (isset($_SESSION['searchStatuss'])) {
            $_SESSION['result'] = $finalResult;
            unset($_SESSION['searchStatuss']);
            return $finalResult;
        } else {
            $pagesCount = $this->pages($this->tableElementSearchedCount());
            $this->view('cities', ['result' => $finalResult, 'countryId' => $countryId, 'pagesCount' => $pagesCount, 'editStatus' => false]);
        }

        return 0;
    }
}
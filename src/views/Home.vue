<template>
  <v-container>
    <div class="home">
      <h1>Mars journay volunteers Register</h1>
      <p>
        Please identify and write down the heroes details who can help us to
        safe life by moving to another planet. <br />
        These astronauts will prepare inhospitable areas to welcome life as we
        know it.
      </p>

      <v-btn rounded color="primary" class="ma-4" @click="openForm(undefined)">
        Add new astronaut to the list
      </v-btn>

      <!-- New Astronaut Form Card -->
      <v-dialog v-model="dialog" persistent max-width="600px">
        <v-card class="pa-4">
          <div class="close_icon" @click="closeDialog()"></div>
          <v-card-title>
            <span class="text-h6 blue--text">{{
              setForUpdate ? "Astronaut - update" : "New Astronaut Profile"
            }}</span>
          </v-card-title>
          <v-form ref="form" v-model="valid" lazy-validation>
            <v-text-field
              v-model="firstName"
              :counter="20"
              :rules="firstNameRules"
              label="First Name"
              required
            ></v-text-field>

            <v-text-field
              v-model="lastName"
              :counter="20"
              :rules="lastNameRules"
              label="Last Name"
              required
            ></v-text-field>

            <v-menu
              ref="menu"
              v-model="menu"
              :close-on-content-click="false"
              :return-value.sync="date"
              transition="scale-transition"
              offset-y
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="date"
                  label="Birth Date"
                  prepend-icon="mdi-calendar"
                  readonly
                  v-bind="attrs"
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker v-model="date" scrollable>
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="menu = false">
                  Cancel
                </v-btn>
                <v-btn text color="primary" @click="$refs.menu.save(date)">
                  OK
                </v-btn>
              </v-date-picker>
            </v-menu>

            <v-select
              v-model="superpower"
              :items="superHeroes"
              label="Superpower As"
            ></v-select>

            <div>
              <v-checkbox
                v-model="checkBoxComp"
                label="Have ever been on the Moon?"
              ></v-checkbox>
              <v-icon v-if="setForUpdate" large @click="deleteAstronaut()"
                >mdi-trash-can-outline</v-icon
              >
            </div>

            <v-btn
              :disabled="!valid"
              color="success"
              class="ma-2"
              @click="sendForm()"
            >
              {{ setForUpdate ? "Update" : "Send" }}
            </v-btn>

            <v-btn color="error" class="ma-2" @click="reset">
              Reset Form
            </v-btn>

            <v-btn color="warning" class="ma-2" @click="resetValidation">
              Reset Validation
            </v-btn>
          </v-form>
        </v-card>
      </v-dialog>

      <!-- Registered Astronauts List -->
      <div class="astronauts_list">
        <v-data-table
          :headers="headers"
          :items="astronauts"
          :items-per-page="5"
          class="table"
          @click:row="openForm"
        ></v-data-table>
      </div>
    </div>
    <MsgBox ref="msgbox" style="position: fixed" />
  </v-container>
</template>

<script>
import axios from "axios";
import MsgBox from "@/components/MsgBox.vue";

export default {
  name: "Home",
  components: {
    MsgBox,
  },
  created() {
    this.loadAstronauts();
  },
  computed: {
    checkBoxComp: {
      get() {
        if (this.onMoon === "yes") {
          return true;
        } else {
          return false;
        }
      },
      set(val) {
        this.onMoon = val;
      },
    },
  },
  data: () => ({
    astronauts: [],
    headers: [
      { text: "ID", value: "id" },
      { text: "First Name", sortable: false, value: "firstName" },
      { text: "Last Name", sortable: false, value: "lastName" },
      { text: "Date of Birth", value: "birthDate", width: "105px" },
      { text: "Superpower As", sortable: false, value: "superpower" },
      { text: "Already on Moon", sortable: false, value: "onMoon" },
    ],
    selectedAstronaut: "",
    dialog: false,
    valid: true,
    firstName: "",
    lastName: "",
    firstNameRules: [
      (v) => !!v || "First Name is required",
      (v) => (v && v.length <= 20) || "Name must be less than 20 characters",
    ],
    lastNameRules: [
      (v) => !!v || "Last Name is required",
      (v) => (v && v.length <= 20) || "Name must be less than 20 characters",
    ],
    date: "",
    menu: false,
    superpower: null,
    superHeroes: [],
    onMoon: "",
    setForUpdate: Boolean,
  }),

  methods: {
    // loads list of astronauts
    loadAstronauts() {
      axios
        .get(this.$myServerPath + "/selectAstronauts.php")
        .then((response) => {
          // console.log(response.data);
          this.astronauts = response.data;
          this.astronauts.forEach((astronaut) => {
            if (astronaut.onMoon === "1") {
              astronaut.onMoon = "yes";
            } else astronaut.onMoon = "no";
            if (astronaut.birthDate === "0000-00-00") {
              astronaut.birthDate = "";
            }
            if (astronaut.superpower === "null") {
              astronaut.superpower = "";
            }
          });
        });
    },
    // will open form for new astronaut or update
    openForm(id) {
      if (id != undefined) {
        // opened for update
        this.selectedAstronaut = id.id;
        this.firstName = id.firstName;
        this.lastName = id.lastName;
        this.date = id.birthDate;
        this.superpower = id.superpower;
        this.onMoon = id.onMoon;
        this.setForUpdate = true;
        this.loadSuperAnimals();
        this.dialog = true;
      } else {
        // opened for new
        this.loadSuperAnimals();
        this.setForUpdate = false;
        this.selectedAstronaut = "";
        this.dialog = true;
        this.$refs.form.reset();
      }
    },
    // loads superpower
    loadSuperAnimals() {
      axios.get("https://swapi.dev/api/people").then((response) => {
        // console.log(response);
        response.data.results.forEach((hero) => {
          this.superHeroes.push(hero.name);
        });
      });
    },

    // sends data to sql
    sendForm() {
      if (this.$refs.form.validate()) {
        if (this.setForUpdate) {
          this.updateAstronaut();
        } else {
          if (
            this.onMoon === "no" ||
            this.onMoon === "" ||
            this.onMoon === false
          ) {
            var onmoon = "0";
          } else {
            onmoon = "1";
          }
          axios
            .post(this.$myServerPath + "/insertIntoSQL.php", {
              insertIntoTable: "Astronauts",
              insertIntoColumns:
                "firstName, lastName, birthDate, superpower, onMoon",
              insertValues: `'${this.firstName}', '${this.lastName}', '${this.date}', '${this.superpower}', '${onmoon}'`,
            })
            .then((response) => {
              // console.log(response.data);
              if (response.data === "sentToSql") {
                this.showMsgBox(
                  "Message",
                  "Data have been sent successfully.",
                  {
                    color: this.$msg_color,
                    noconfirm: true,
                    width: 300,
                    height: 150,
                  }
                );
                this.dialog = false;
                setTimeout(() => {
                  this.loadAstronauts();
                }, 1000);
              } else {
                this.showMsgBox("Warning", "Data have not been sent.", {
                  color: this.$warning_msg_color,
                  noconfirm: true,
                  width: 300,
                  height: 150,
                });
              }
            });
        }
      }
    },
    // updates SQL records
    updateAstronaut() {
      if (this.onMoon === "no" || this.onMoon === "" || this.onMoon === false) {
        var onmoon = 0;
      } else {
        onmoon = 1;
      }
      axios
        .post(this.$myServerPath + "/updateAstronaut.php", {
          astronautID: this.selectedAstronaut,
          setString: `firstName = '${this.firstName}', lastName = '${this.lastName}', birthDate = '${this.date}', superpower = '${this.superpower}', onMoon = '${onmoon}'`,
        })
        .then((response) => {
          // console.log(response.data);
          if (response.data === "SqlUpdated") {
            this.showMsgBox("Message", "Data have been updated successfully.", {
              color: this.$msg_color,
              noconfirm: true,
              width: 300,
              height: 150,
            });
            this.dialog = false;
          } else {
            this.showMsgBox("Warning", "Data have not been updated.", {
              color: this.$warning_msg_color,
              noconfirm: true,
              width: 300,
              height: 150,
            });
          }
        });
      setTimeout(() => {
        this.loadAstronauts();
      }, 1000);
    },
    // deletes Astronaut from SQL
    async deleteAstronaut() {
      if (
        await this.$refs.msgbox.open("Confirm", "Astronaut will be deleted.", {
          color: this.$dialog_msg_color,
          noconfirm: false,
          width: "300",
          height: "150",
        })
      ) {
        axios
          .post(this.$myServerPath + "/deleteRec.php", {
            table: "Astronauts",
            column: "id",
            recID: `${this.selectedAstronaut}`,
          })
          .then(() => {
            this.dialog = false;
            this.loadAstronauts();
          });
      }
    },
    // closes form dialog
    closeDialog() {
      this.dialog = false;
    },
    // resets form
    reset() {
      this.$refs.form.reset();
    },
    // resets form validation
    resetValidation() {
      this.$refs.form.resetValidation();
    },
    // shows MsgBox
    async showMsgBox(nadpis, zprava, options) {
      if (await this.$refs.msgbox.open(nadpis, zprava, options)) {
      }
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}
.home {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  max-width: 768px;
  padding: 40px;
  border: 1px solid white;
  border-radius: 20px;
  color: white;
  background-color: rgba(0, 0, 216, 0.2);
}
.close_icon {
  position: absolute;
  top: 0;
  right: 15px;
  font-size: 31px;
  color: black;
  cursor: pointer;
}
.close_icon:before {
  content: "×";
}
.astronauts_list {
  margin: 20px;
  overflow: auto;
}
.table >>> th {
  font-weight: 800;
}
.table >>> td {
  cursor: pointer;
}

@media (max-width: 768px) {
  .home {
    max-width: 640px;
    padding: 10px;
  }
}

@media (max-width: 576px) {
  .home {
    max-width: 360px;
  }
}
</style>
